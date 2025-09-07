<?php

namespace App\Models\Concerns;

use App\Models\Student;
use App\Scopes\StudentScope;

trait BelongsToStudent
{
    public static function bootBelongsToTeacher(): void
    {
        static::addGlobalScope(new StudentScope());
        static::creating(function ($model): void {
            if (!$model->getAttribute('student_id') && !$model->relationLoaded('student_id')) {
                if (request()->teacher_id) {
                    $model->setAttribute('student_id', request()->student_id);
                }
            }
        });
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
