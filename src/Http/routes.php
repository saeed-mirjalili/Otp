<?php
use Illuminate\Support\Facades\Route;
use \Saeed\Otp\Http\Controllers\WhatsappAuthController;
use \Saeed\Otp\Http\Controllers\TelegramAuthController;

Route::get('/login', [WhatsappAuthController::class, 'showLoginForm'])->middleware('web')->name('login');
Route::get('/register', [WhatsappAuthController::class, 'showRegisterForm'])->middleware('web')->name('register');
Route::get('/logout', [WhatsappAuthController::class, 'logout'])->middleware('web')->name('logout');

Route::prefix('whatsappOtp')->group(function () {
    Route::post('/send-otp', [WhatsappAuthController::class, 'sendOtp'])->middleware('web')->name('send-otp');
    Route::post('/verify-otp', [WhatsappAuthController::class, 'verifyOtp'])->middleware('web')->name('verify-otp');
});

Route::prefix('telegramOtp')->group(function (){
    Route::post('/',[TelegramAuthController::class, 'telegramHook'])->middleware('web')->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)->name('telegramHook');
    Route::post('/start', [TelegramAuthController::class, 'start'])->middleware('web')->name('telegram.start');
    Route::post('/callback', [TelegramAuthController::class, 'handleCallbackQuery'])->middleware('web');
    Route::post('/verifyOtpTelegram', [TelegramAuthController::class, 'verifyOtpTelegram'])->middleware('web')->name('verifyOtpTelegram');
});
//->middleware('web') برای حل مشکل session در پکیج استفاده شده
