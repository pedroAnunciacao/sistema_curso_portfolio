<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class StudentScope implements Scope
{
    public function apply(Builder $query, Model $model): void
    {
        if (!request()->student_id) {
            return;
        }

        $query->where('student_id', request()->student_id);
    }
}
