<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

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
        "id",
        "name",
        "slug",
        "avatar",
        "status",
        "major_category_id",
    ];

    const CATEGORY_STATUS = [
        'ACTIVE' => 'active',
        'INACTIVE' => 'inactive',
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

    public function major_categories()
    {
        return $this->belongsTo(Major_Category::class, 'major_category_id');
    }

    public function productions()
    {
        return $this->hasMany(Production::class, 'category_id', 'id');
    }
}
