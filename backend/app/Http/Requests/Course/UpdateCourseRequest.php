<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course.id' => ['required', 'integer'],
            'course.title' => 'nullable|string|max:255',
            'course.description' => 'nullable|string',
            'course.image' => 'nullable|string',
            'course.price' => 'nullable|numeric|min:0',

        ];
    }
}
