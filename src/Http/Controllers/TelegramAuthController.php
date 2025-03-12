<?php

namespace Saeed\Otp\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Saeed\Otp\Models\OtpUser;
use Saeed\Otp\TelegramOtpService;

class TelegramAuthController
{
    protected $TelegramOtpService;

    public function __construct(TelegramOtpService $TelegramOtpService)
    {
        $this->TelegramOtpService = $TelegramOtpService;
    }

    public function telegramHook(Request $request)
    {
        $update = $request->all();

        if (isset($update['callback_query'])) {
            $this->handleCallbackQuery($update['callback_query']);
        } else {
            $messageText = $update['message']['text'];
            $username = $update['message']['from']['username'];
            $chatId = $update['message']['chat']['id'];

            OtpUser::updateOrCreate(
                ['name' => $username],
                ['phone' => $chatId, 'uuid' => $messageText]
            );
            $this->TelegramOtpService->handleMessage($update);
        }

        return response('OK', 200);
    }

    private function handleCallbackQuery($callbackQuery)
    {
        $callbackData = $callbackQuery['data'];
        $chatId = $callbackQuery['message']['chat']['id'];
        $username = $callbackQuery['message']['chat']['username'];

        if ($callbackData === 'get_otp') {
            $otp = $this->TelegramOtpService->generateOtp();
            $message = "Your OTP code is: " . $otp;
            $this->TelegramOtpService->sendTelegramMessage($chatId, $message);

            OtpUser::updateOrCreate(
                ['name' => $username],
                ['phone' => $chatId, 'otp' => $otp]
            );

            $this->TelegramOtpService->answerCallbackQuery($callbackQuery['id'], 'One-time password sent.');
        }
    }

    public function verifyOtpTelegram(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'required|string',
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = OtpUser::where('uuid', "/start " . $request->uuid)->first();

        if ($user && $user->otp == $request->otp) {
            Auth::login($user);
            return redirect()->route('home');
        }

        return redirect()->back()->with('error', 'Invalid OTP')->withInput($request->except('otp'));
    }
}
