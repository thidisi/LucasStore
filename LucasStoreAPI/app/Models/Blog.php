<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Blog extends Model
{
    use HasFactory, SoftDeletes, Uuid;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blogs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "id",
        "title",
        "slug",
        "content",
        "image",
        "count_view",
        "status"
    ];

    protected $keyType = 'uuid';

    public $incrementing = false;

    const BLOG_STATUS = [
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

    public function getFormatDateAttribute()
    {
        return date("d-M-Y", strtotime($this->created_at)) . "\n";
    }
}
