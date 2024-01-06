<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'slug',
        'description',
        'status',
    ];

    const PERMISSION_STATUS = [
        'ACTIVE' => 'active',
        'INACTIVE' => 'inactive',
    ];

    const PERMISSION_ID = [
        'PERMISSION' => '1',
        'SETTING-KPI' => '2',
        'BONUS' => '3',
        'SETTING-JOB' => '4',
        'CRUD-JOB' => '5',
        'SHOW-JOB' => '6',
    ];

    public $timestamps = true;

    /**
     * Return the created_at configuration array for this model.
     *
     * @return array
     */
    protected $casts = [
        //
    ];
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'permission_id', 'role_id');
    }
}
