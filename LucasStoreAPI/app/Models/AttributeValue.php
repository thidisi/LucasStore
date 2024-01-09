<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class AttributeValue extends Model
{
    use HasFactory, SoftDeletes, Uuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'attribute_values';

    protected $fillable = [
        'id',
        'attribute_id',
        'name',
        'slug',
        'descriptions',
        'status',
    ];

    const ATTRIBUTE_VALUE_STATUS = [
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

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    // public function productions()
    // {
    //     return $this->belongsToMany(Production::class, 'production_attr_value', 'production_id', 'attribute_value_id')->withTimestamps();
    // }
}
