<?php

namespace App\Http\Requests\Enrollment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'enrollment.id' => 'required|integer',
            'enrollment.student_id' => 'required|integer',
            'enrollment.course_id' => 'required|integer',
        ];
    }
}
