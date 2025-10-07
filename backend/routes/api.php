<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WebhooksController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;

use App\Http\Middleware\BindRequestFilter;

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:api')->get('me', [AuthController::class, 'me']);
});

/*
|--------------------------------------------------------------------------
| WebHooks
|--------------------------------------------------------------------------
*/
Route::prefix('webhooks')->group(function () {
    Route::post('postback/mercadopago', [WebhooksController::class, 'postbackMercadoPago']);
});

/*
|--------------------------------------------------------------------------
| Resources protegidos
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api', BindRequestFilter::class])->group(function () {

    
    /*
    |--------------------------------------------------------------------------
    | Courses Module
    |--------------------------------------------------------------------------
    */
    Route::prefix('courses')->group(function () {
        Route::post('/', [CourseController::class, 'store']);
        Route::put('/', [CourseController::class, 'update']);
        Route::get('/', [CourseController::class, 'index']);
        Route::get('by-teacher/{teacher_id}', [CourseController::class, 'byTeacher']);
        Route::get('{courseId}', [CourseController::class, 'show']);
        Route::delete('{courseId}', [CourseController::class, 'destroy']);
    });


    /*
    |--------------------------------------------------------------------------
    | Audit Module
    |--------------------------------------------------------------------------
    */
    Route::prefix('audit')->group(function () {
        Route::get('/', [AuditController::class, 'index']);
    });

    /*
    |--------------------------------------------------------------------------
    | Audit payment
    |--------------------------------------------------------------------------
    */
    Route::prefix('payments')->group(function () {
    });

    /*
    |--------------------------------------------------------------------------
    | People Module
    |--------------------------------------------------------------------------
    */
    Route::prefix('people')->group(function () {
        Route::post('/', [PersonController::class, 'store']);
        Route::get('/', [PersonController::class, 'index']);
        Route::put('/', [PersonController::class, 'update']);
        Route::get('{personId}', [PersonController::class, 'show']);
        Route::delete('{personId}', [PersonController::class, 'destroy']);
    });


    /*
    |--------------------------------------------------------------------------
    | teacher Module
    |--------------------------------------------------------------------------
    */
    Route::prefix('teachers')->group(function () {
        Route::get('/', [TeacherController::class, 'index']);
    });

    /*
    |--------------------------------------------------------------------------
    | student Module
    |--------------------------------------------------------------------------
    */
    Route::prefix('students')->group(function () {
        Route::get('/', [StudentController::class, 'index']);
    });

    /*
    |--------------------------------------------------------------------------
    | Lessons Module
    |--------------------------------------------------------------------------
    */
    Route::prefix('lessons')->group(function () {
        Route::post('/', [LessonController::class, 'store']);
        Route::get('/', [LessonController::class, 'index']);
        Route::put('/', [LessonController::class, 'update']);
        Route::get('{lessonId}', [LessonController::class, 'show']);
        Route::delete('{lessonId}', [LessonController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Enrollments Module
    |--------------------------------------------------------------------------
    */
    Route::prefix('enrollments')->group(function () {
        Route::post('/', [EnrollmentController::class, 'store']);
        Route::get('/', [EnrollmentController::class, 'index']);
        Route::put('/', [EnrollmentController::class, 'update']);
        Route::get('{enrollmentId}', [EnrollmentController::class, 'show']);
        Route::delete('{enrollmentId}', [EnrollmentController::class, 'destroy']);
    });
});

/*
|--------------------------------------------------------------------------
| Payments
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api', 'checkout'])->prefix('payments')->group(function () {
    Route::post('pix', [PaymentController::class, 'processPixPayment']);
    Route::post('credit-card', [PaymentController::class, 'processCardPayment']);
    Route::post('boleto', [PaymentController::class, 'processBoletoPayment']);
    Route::get('/', [CheckoutController::class, 'index']);
});
