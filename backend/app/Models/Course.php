<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToTeacher;

class Course extends Model
{
    use HasFactory, SoftDeletes, BelongsToTeacher;

    protected $fillable = [
        'title',
        'description',
        'teacher_id',
    ];


    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function teacher()
    {
        return $this->hasMany(Teacher::class);
    }




    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments')->withTimestamps();
    }
}
