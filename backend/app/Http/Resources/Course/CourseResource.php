<?php

namespace App\Http\Resources\Course;

use App\Models\Teacher;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Lesson\LessonResource;
use App\Http\Resources\Person\PersonResource;
use App\Http\Resources\Teacher\TeacherResource;
class CourseResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image ??  null,
            'teacher' => new TeacherResource($this->whenLoaded('teacher')),
            'lessons' => LessonResource::collection($this->whenLoaded('lessons')),
            'students' => PersonResource::collection($this->whenLoaded('students')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
