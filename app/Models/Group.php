<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Group extends Model
{
    use AsSource;
    use HasFactory;

    protected $fillable = ['name', 'teacher_id'];

    public function scopeOwnedGroup(Builder $query, $user_id)
    {
        return $query->where('teacher_id', $user_id);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'group_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'student_groups', 'group_id', 'user_id');
    }
}
