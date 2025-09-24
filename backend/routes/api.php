<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\CheckoutController;
use App\Http\Middleware\BindRequestFilter;

// Auth
Route::post('/auth/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->get('/me', [AuthController::class, 'me']);

// WebHooks
Route::post('checkout/postback', [CheckoutController::class, 'postback']);

// API Resources protegidos
Route::middleware(
    [
        'auth:api',
        BindRequestFilter::class,
    ]
)->group(function () {

/*
|--------------------------------------------------------------------------
| courses Module
|--------------------------------------------------------------------------
*/
    Route::post('courses', [CourseController::class, 'store']);
    Route::put('courses/{courseId}', [CourseController::class, 'update']);
    Route::get('courses', [CourseController::class, 'index']);
    Route::get('/courses/by-teacher', [CourseController::class, 'byTeacher']);
    Route::get('courses/{courseId}', [CourseController::class, 'show']);
    Route::delete('courses/{courseId}', [CourseController::class, 'destroy']);
    
/*
|--------------------------------------------------------------------------
| people Module
|--------------------------------------------------------------------------
*/
    Route::post('people/store', [PersonController::class, 'store']);
    Route::get('people/index', [PersonController::class, 'index']);
    Route::put('people/update', [PersonController::class, 'update']);
    Route::get('people/show/{personId}', [PersonController::class, 'show']);
    Route::delete('people/delete/{personId}', [PersonController::class, 'destroy']);
    
/*
|--------------------------------------------------------------------------
| lessons Module
|--------------------------------------------------------------------------
*/
    Route::apiResource('lessons', LessonController::class);


/*
|--------------------------------------------------------------------------
| enrollments Module
|--------------------------------------------------------------------------
*/
    Route::apiResource('enrollments', EnrollmentController::class);
});

Route::middleware(['auth:api', 'checkout'])->group(function () {
    Route::post('checkout/pix', [CheckoutController::class, 'pix']);
    Route::post('checkout/card', [CheckoutController::class, 'card']);
    Route::post('checkout/boleto', [CheckoutController::class, 'boleto']);
    Route::post('checkout/index', [CheckoutController::class, 'index']);
});
