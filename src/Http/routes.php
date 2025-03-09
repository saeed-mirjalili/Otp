<?php
use Illuminate\Support\Facades\Route;
use \Saeed\Otp\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->middleware('web')->name('login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->middleware('web')->name('register');
Route::get('/logout', [AuthController::class, 'logout'])->middleware('web')->name('logout');
Route::post('/send-otp', [AuthController::class, 'sendOtp'])->middleware('web')->name('send-otp');
//Route::get('/whatsappHook', [AuthController::class, 'whatsappHook'])->name('whatsappHook');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->middleware('web')->name('verify-otp');

Route::prefix('telegramOtp')->group(function (){
    Route::post('/',[AuthController::class, 'telegramHook'])
        ->middleware('web')
        ->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
        ->name('telegramHook');

    Route::post('/start', [AuthController::class, 'start'])
        ->middleware('web')
//        ->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
        ->name('telegram.start');

    Route::post('/callback', [AuthController::class, 'handleCallbackQuery'])
        ->middleware('web')
//        ->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        ;
    Route::get('/verify-otp', [AuthController::class, 'verifyOtp']);
});
//->middleware('web') برای حل مشکل session در پکیج استفاده شده
