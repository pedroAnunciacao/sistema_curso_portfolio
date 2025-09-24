<?php

namespace App\Traits;

use App\Models\Course;

trait ResolveTeacherIdForModelType
{
    protected function resolveTeacherId(string $model_type, int $model_id)
    {
        if($model_type == "courses"){
          return  $this->getTeacherIdOnCouse($model_id);
        }
    }

    public function getTeacherIdOnCouse(int $model_id)
    {

        return Course::where('id', $model_id)->first()->teacher_id;
    }
}
