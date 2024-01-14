<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Support\Str;

class Major_Category extends Model
{
    use HasFactory, Uuid;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'major_categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "id",
        "name",
        "slug",
        "status"
    ];

    const MENU_STATUS = [
        'SHOW' => 'show',
        'HOT_DEFAULT' => 'hot_default',
        'HIDE' => 'hide',
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

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    protected static function booted(): void
    {
        static::created(static function (Major_Category $major_category) {
            static::clearCache($major_category);
        });

        static::updated(static function (Major_Category $major_category) {
            static::clearCache($major_category);
        });

        static::deleted(static function (Major_Category $major_category) {
            static::clearCache($major_category);
        });
    }

    private static function clearCache(Major_Category $major_category): void
    {
        cache()->forget('config_major_categories');
        cache()->forget('config_major_category_' . $major_category->id);
    }

    public static function getAndWithCache($param = null)
    {
        $json = cache()->remember('config_major_categories', self::ONE_MONTH, function () use ($param) {
            // != Major_Category::MENU_STATUS['HOT_DEFAULT']
            if (isset($param)) {
                return self::query()->where('status', '!=', $param)->latest('created_at')->get()?->toJson();
            }
            return self::query()->latest('created_at')->get()?->toJson();
        });
        return json_decode($json);
    }

    public static function findAndWithCache($id)
    {
        if (isset(self::$cache[$id])) {
            return self::$cache[$id];
        }
        $json = cache()->remember('config_major_category_' . $id, self::ONE_MONTH, function () use ($id) {
            return self::query()->with('category')->findOrFail($id)?->toJson();
        });
        return json_decode($json);
    }

    public function category()
    {
        return $this->hasMany(Category::class, 'major_category_id');
    }

    // public function slide()
    // {
    //     return $this->hasMany(Slide::class, 'major_category_id');
    // }
}
