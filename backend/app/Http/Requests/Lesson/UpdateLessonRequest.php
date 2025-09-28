<?php

namespace App\Http\Requests\Lesson;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lesson.id' => 'nullable|integer',
            'lesson.course_id' => 'nullable|integer',
            'lesson.title' => 'required|string|max:255',
            'lesson.content' => 'nullable|string',
            'lesson.image' => 'nullable|string',
            'lesson.link_youtube' => 'nullable|string',
        ];
    }
}
