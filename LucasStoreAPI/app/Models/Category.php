<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes, Uuid;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'slug',
        'avatar',
        'status',
        'major_category_id',
    ];

    const ONE_MONTH = 60 * 60 * 24 * 30;

    protected $keyType = 'uuid';

    private static array $caches = [];

    public $incrementing = false;

    public $timestamps = true;

    /**
     * Return the created_at configuration array for this model.
     *
     * @return array
     */
    protected $casts = [
        'created_at' => 'date:d-m-Y',
        'updated_at' => 'date:d-m-Y'
    ];

    const CATEGORY_STATUS = [
        'ACTIVE' => 'active',
        'INACTIVE' => 'inactive',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    protected static function booted(): void
    {
        static::created(static function (Category $category) {
            static::clearCache($category);
        });

        static::updated(static function (Category $category) {
            static::clearCache($category);
        });

        static::deleted(static function (Category $category) {
            static::clearCache($category);
        });
    }

    private static function clearCache(Category $category): void
    {
        cache()->forget('config_categories');
        cache()->forget('config_category_' . $category->id);
    }

    public static function getAndWithCache()
    {
        $json = cache()->remember('config_categories', self::ONE_MONTH, function () {
            return self::query()->with('major_category')->latest('created_at')->get()?->toJson();
        });
        return json_decode($json);
    }

    public static function findAndWithCache($id)
    {
        if (isset(self::$cache[$id])) {
            return self::$cache[$id];
        }
        $json = cache()->remember('config_category_' . $id, self::ONE_MONTH, function () use ($id) {
            return self::query()->with('major_category')->findOrFail($id)?->toJson();
        });
        return json_decode($json);
    }

    public function major_category()
    {
        return $this->belongsTo(Major_Category::class, 'major_category_id');
    }

    // public function productions()
    // {
    //     return $this->hasMany(Production::class, 'category_id', 'id');
    // }
}
