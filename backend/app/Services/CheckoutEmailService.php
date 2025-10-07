<?php

namespace App\Services;

use App\Models\Checkout;
use App\Services\StudentService;
use App\Services\TeacherService;
use App\Services\CourseService;
use Illuminate\Support\Facades\Mail;
use App\Mail\CheckoutConfirmationMail;

class CheckoutEmailService
{
    protected $studentService;
    protected $teacherService;
    protected $courseService;

    public function __construct(
        StudentService $studentService,
        TeacherService $teacherService,
        CourseService $courseService
    ) {
        $this->studentService = $studentService;
        $this->teacherService = $teacherService;
        $this->courseService  = $courseService;
    }

    public function sendConfirmation(Checkout $checkout)
    {
        $student = $this->studentService->show($checkout->student_id);
        $teacher = $this->teacherService->show($checkout->teacher_id);
        $course  = $this->courseService->show($checkout->model_id); // se model for Course

        Mail::to($student->email_educacional)->send(new CheckoutConfirmationMail(
            $student,
            $teacher,
            $course,
            $checkout
        ));
    }
}
