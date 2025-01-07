<?php
use Illuminate\Support\Facades\Route;
use \Saeed\Otp\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->middleware('web')->name('login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->middleware('web')->name('register');
Route::get('/logout', [AuthController::class, 'logout'])->middleware('web')->name('logout');
Route::post('/send-otp', [AuthController::class, 'sendOtp'])->middleware('web')->name('send-otp');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->middleware('web')->name('verify-otp');


//->middleware('web') برای حل مشکل session در پکیج استفاده شده
