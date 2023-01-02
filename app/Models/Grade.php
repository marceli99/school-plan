<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = ['value', 'weight', 'student_id', 'group_id', 'teacher_id'];

    public function scopeOwnedBy($query, $user)
    {
        return $query->where('student_id', $user->id);
    }

    public function scopeInGroup($query, $group)
    {
        return $query->where('group_id', $group->id);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
