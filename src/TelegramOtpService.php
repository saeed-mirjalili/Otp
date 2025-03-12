<?php

namespace Saeed\Otp;

class TelegramOtpService
{
    protected $TELEGRAM_BOT_TOKEN;

    public function __construct()
    {
        $this->TELEGRAM_BOT_TOKEN = config('otp.TELEGRAM_BOT_TOKEN');
    }

    public function generateOtp($length = 6)
    {
        return rand(pow(10, $length - 1), pow(10, $length) - 1);
    }

    public function answerCallbackQuery($callbackQueryId, $text)
    {
        $botToken = $this->TELEGRAM_BOT_TOKEN;
        $apiUrl = "https://api.telegram.org/bot{$botToken}/answerCallbackQuery";

        $data = [
            'callback_query_id' => $callbackQueryId,
            'text' => $text
        ];

        $this->sendTelegramRequest($apiUrl, $data);
    }

    public function sendTelegramMessage($chatId, $message, $replyMarkup = null)
    {
        $botToken = $this->TELEGRAM_BOT_TOKEN;
        $apiUrl = "https://api.telegram.org/bot{$botToken}/sendMessage";

        $data = [
            'chat_id' => $chatId,
            'text' => $message,
        ];

        if ($replyMarkup) {
            $data['reply_markup'] = $replyMarkup;
        }

        $this->sendTelegramRequest($apiUrl, $data);
    }

    public function sendTelegramRequest($apiUrl, $data)
    {
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function handleMessage($update)
    {
        if (isset($update['message'])) {
            $chatId = $update['message']['chat']['id'];
            $username = $update['message']['chat']['username'];
            $this->start($chatId);
        }
    }

    public function start($chatId)
    {
        $keyboard = [
            [
                ['text' => 'Receive a one-time password', 'callback_data' => 'get_otp']
            ]
        ];

        $replyMarkup = json_encode([
            'inline_keyboard' => $keyboard
        ]);

        $messageText = 'To receive a one-time password, click the button below:';
        $this->sendTelegramMessage($chatId, $messageText, $replyMarkup);

        return response('OK', 200);
    }
}
