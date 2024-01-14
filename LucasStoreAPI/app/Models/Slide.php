<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory, Uuid;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'slide';

    protected $fillable = [
        'id',
        'title',
        'slug',
        'image',
        'major_category_id',
        'sort_order',
        'status',
    ];

    protected $keyType = 'uuid';

    public $incrementing = false;

    public $timestamps = true;

    const SLIDE_STATUS = [
        'ACTIVE' => 'active',
        'INACTIVE' => 'inactive',
    ];

    const SLIDE_ORDER = [
        'SLIDER' => 'slider',
        'INSTAGRAM' => 'instagram',
        'BANNER' => 'banner',
        'HIDE' => 'hide',
    ];

    /**
     * Return the created_at configuration array for this model.
     *
     * @return array
     */
    protected $casts = [
        'created_at' => 'date:d-m-Y',
        'updated_at' => 'date:d-m-Y'
    ];

    public function major_category()
    {
        return $this->belongsTo(Major_Category::class, 'major_category_id');
    }
}
