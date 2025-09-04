<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\PessoaController;
use App\Http\Controllers\CheckoutController;



// Auth
Route::post('/auth/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->get('/me', [AuthController::class, 'me']);
Route::post('checkout/postback', [CheckoutController::class, 'postback']);

// API Resources protegidos
Route::middleware(
    [
        'auth:api',
        \App\Http\Middleware\BindPessoaId::class,
        \App\Http\Middleware\LoadClientConfig::class
    ]
)->group(function () {
    Route::apiResource('courses', CourseController::class);
    Route::apiResource('pessoas', PessoaController::class);
    Route::apiResource('lessons', LessonController::class);
    Route::apiResource('enrollments', EnrollmentController::class);
    Route::post('checkout/pix', [CheckoutController::class, 'pix']);
    Route::post('checkout/card', [CheckoutController::class, 'card']);
    Route::post('checkout/boleto', [CheckoutController::class, 'boleto']);
    Route::post('checkout/index', [CheckoutController::class, 'index']);
});
