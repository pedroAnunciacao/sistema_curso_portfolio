<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course.title' => 'required|string|max:255',
            'course.description' => 'nullable|string',
            'course.image' => 'nullable|string',
        ];
    }
}
