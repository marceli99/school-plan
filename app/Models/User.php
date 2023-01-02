<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Platform\Models\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'permissions',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'permissions',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'permissions'          => 'array',
        'email_verified_at'    => 'datetime',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'name',
        'email',
        'permissions',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'email',
        'updated_at',
        'created_at',
    ];

    public function scopeInGroup(Builder $query, $group_id)
    {
        return $query->where('group_id', $group_id);
    }

    public function studentGroups()
    {
        return $this->belongsToMany(Group::class, 'student_groups', 'user_id', 'group_id');
    }

    public function ownedGroups()
    {
        return $this->hasMany(Group::class, 'teacher_id');
    }

    public function grades() {
        return $this->hasMany(Grade::class, 'student_id');
    }
}
