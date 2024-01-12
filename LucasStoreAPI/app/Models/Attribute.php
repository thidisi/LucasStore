<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Attribute extends Model
{
    use HasFactory, SoftDeletes;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'attributes';

    protected $fillable = [
        'id',
        'name',
        'slug',
        'descriptions',
        'status',
        'replace_id',
    ];

    const ATTRIBUTE_STATUS = [
        'ACTIVE' => 'active',
        'INACTIVE' => 'inactive',
    ];

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

    public function attribute_value()
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id');
    }

    public function replace()
    {
        return $this->hasMany(self::class, 'replace_id', 'id');
    }
}
