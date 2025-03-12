<?php
use Illuminate\Support\Facades\Route;
use \Saeed\Otp\Http\Controllers\AuthController;
use \Saeed\Otp\Http\Controllers\TelegramAuthController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->middleware('web')->name('login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->middleware('web')->name('register');
Route::get('/logout', [AuthController::class, 'logout'])->middleware('web')->name('logout');

Route::prefix('whatsappOtp')->group(function () {
    Route::post('/send-otp', [AuthController::class, 'sendOtp'])->middleware('web')->name('send-otp');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->middleware('web')->name('verify-otp');
});

Route::prefix('telegramOtp')->group(function (){
    Route::post('/',[TelegramAuthController::class, 'telegramHook'])->middleware('web')->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)->name('telegramHook');
    Route::post('/start', [TelegramAuthController::class, 'start'])->middleware('web')->name('telegram.start');
    Route::post('/callback', [TelegramAuthController::class, 'handleCallbackQuery'])->middleware('web');
    Route::post('/verifyOtpTelegram', [TelegramAuthController::class, 'verifyOtpTelegram'])->middleware('web')->name('verifyOtpTelegram');
});
//->middleware('web') برای حل مشکل session در پکیج استفاده شده
