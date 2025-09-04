<?php

namespace App\Models\Concerns;

use App\Models\Teacher;
use App\Scopes\TeacherScope;

trait BelongsToTeacher
{
    public static function bootBelongsToTeacher(): void
    {
        static::addGlobalScope(new TeacherScope());
        static::creating(function ($model): void {
            if (!$model->getAttribute('teacher_id') && !$model->relationLoaded('teacher')) {
                if (request()->teacher_id) {
                    $model->setAttribute('teacher_id', request()->teacher_id);
                }
            }
        });
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
