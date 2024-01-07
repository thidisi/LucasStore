<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use Illuminate\Support\Str;

class Major_Category extends Model
{
    use HasFactory, SoftDeletes, Uuid;
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
    protected $keyType = 'uuid';

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

    public function category()
    {
        return $this->hasMany(Category::class, 'major_category_id');
    }

    // public function slide()
    // {
    //     return $this->hasMany(Slide::class, 'major_category_id');
    // }
}
