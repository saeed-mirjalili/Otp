<?php
//namespace Saeed\Otp\Http\Controllers;
//
//use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Facades\Session;
//use Saeed\Otp\Models\OtpUser;
//use Saeed\Otp\OtpService;
//
//class AuthController extends Controller
//{
//    private $uuuid;
//    protected $otpService;
//
//    public function __construct(OtpService $otpService)
//    {
//        $this->otpService = $otpService;
//    }
//
//    public function showLoginForm()
//    {
//        return view('otp::login');
//    }
//
//    public function showRegisterForm()
//    {
//        return view('otp::register');
//    }
//
//    public function sendOtp(Request $request)
//    {
//        $validData = $request->validate([
//            'name' => 'required|string',
//            'phone' => 'string|max:15'
//        ]);
//        $otp = $this->otpService->generateOtp();
//        if ($request->has('phone')){
//            $this->otpService->sendOtp($otp, $request->phone);
//            session(['otp' => $otp,'phone' => $request->phone, 'name' => $validData['name']]);
//        }else{
//            $user = OtpUser::where('name', $request->name)->first();
//            if (is_null($user)){
//                return redirect()->back()->with('error', 'OtpUser not found');
//            }
//            $this->otpService->sendOtp($otp, $user->phone);
//            session(['otp' => $otp,'phone' => $user->phone, 'name' => $validData['name']]);
//        }
//        return redirect()->back()->with('success', 'otp sent successfully');
//    }
//
//    public function verifyOtp(Request $request)
//    {
//        $user = OtpUser::where('uuid', "/start ".$request->uuid)->first();
//        if ($user->otp == $request->otp){
//            Auth::login($user);
//            return redirect()->route('home');
//        }
//        return redirect()->back()->with('error', 'Invalid Otp');
//    }
//
//    public function logout()
//    {
//        Auth::logout();
//        Session::forget('name');
//        Session::forget('otp');
//        return redirect('/')->with('success', 'you logged out successfully ');
//    }
//
//
//    public function telegramHook(Request $request)
//    {
//        $update = $request->all();
//
//        if (isset($update['callback_query'])) {
//
//            $this->handleCallbackQuery($update['callback_query']);
//        } else {
////            Log::info($update['message']['text']);
////            $this->uuuid = $update['message']['text'];
//            OtpUser::updateOrCreate(
//                ['name'=>$update['message']['from']['username']],
//                ['phone'=>$update['message']['chat']['id'], 'uuid'=>$update['message']['text']]
//            );
//            $this->handleMessage($update);
//        }
//
//        return response('OK', 200);
//    }
//
//    private function handleCallbackQuery($callbackQuery)
//    {
//        $callbackData = $callbackQuery['data'];
//        $chat_id = $callbackQuery['message']['chat']['id'];
//        $name = $callbackQuery['message']['chat']['username'];
//
//        if ($callbackData === 'get_otp') {
//            $otp = $this->otpService->generateOtp();
//            $message = "Your OTP code is: " . $otp;
//            $this->sendTelegramMessage($chat_id, $message);
//            OtpUser::updateOrCreate(
//                ['name'=>$name],
//                ['phone'=>$chat_id, 'otp'=>$otp]
//            );
//            $this->name = $name;
//            $this->answerCallbackQuery($callbackQuery['id'], 'One-time password sent.');
//        }
//    }
//
//    private function handleMessage($update)
//    {
//        if (isset($update['message'])) {
//            $chat_id = $update['message']['chat']['id'];
//            $name = $update['message']['chat']['username'];
//            $this->start($chat_id, $name);
//        }
//    }
//
//    public function start($chat_id, $name)
//    {
//        $keyboard = [
//            [
//                ['text' => 'Receive a one-time password', 'callback_data' => 'get_otp']
//            ]
//        ];
//
//        $replyMarkup = json_encode([
//            'inline_keyboard' => $keyboard
//        ]);
//
//        $botToken = config('otp.TELEGRAM_BOT_TOKEN');
//        $apiUrl = "https://api.telegram.org/bot{$botToken}/sendMessage";
//
//        $data = [
//            'chat_id' => $chat_id,
//            'text' => 'To receive a one-time password, click the button below:',
//            'reply_markup' => $replyMarkup
//        ];
//
//        $ch = curl_init($apiUrl);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        $response = curl_exec($ch);
//        curl_close($ch);
//
//        return response('OK', 200);
//    }
//
//    private function sendTelegramMessage($chat_id, $message)
//    {
//        $botToken = config('otp.TELEGRAM_BOT_TOKEN');
//        $apiUrl = "https://api.telegram.org/bot{$botToken}/sendMessage";
//
//        $data = [
//            'chat_id' => $chat_id,
//            'text' => $message
//        ];
//
//        $ch = curl_init($apiUrl);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        $response = curl_exec($ch);
//        curl_close($ch);
//    }
//
//    private function answerCallbackQuery($callbackQueryId, $text)
//    {
//        $botToken = config('otp.TELEGRAM_BOT_TOKEN');
//        $apiUrl = "https://api.telegram.org/bot{$botToken}/answerCallbackQuery";
//
//        $data = [
//            'callback_query_id' => $callbackQueryId,
//            'text' => $text
//        ];
//
//        $ch = curl_init($apiUrl);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        $response = curl_exec($ch);
//        curl_close($ch);
//    }
//}


namespace Saeed\Otp\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Saeed\Otp\Models\OtpUser;
use Saeed\Otp\OtpService;

class AuthController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function showLoginForm()
    {
        return view('otp::login');
    }

    public function showRegisterForm()
    {
        return view('otp::register');
    }

    public function sendOtp(Request $request)
    {
        $validData = $request->validate([
            'name' => 'required|string',
            'phone' => 'string|max:15'
        ]);

        $otp = $this->otpService->generateOtp();

        if ($request->has('phone')) {
            $phone = $request->phone;
        } else {
            $user = OtpUser::where('name', $request->name)->first();
            if (!$user) {
                return redirect()->back()->with('error', 'OtpUser not found');
            }
            $phone = $user->phone;
        }

        $this->otpService->sendOtp($otp, $phone);
        session(['otp' => $otp, 'phone' => $phone, 'name' => $validData['name']]);

        return redirect()->back()->with('success', 'OTP sent successfully');
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'required|string',
            'otp' => 'required|digits:6', // Assuming OTP is a 6-digit number
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

    public function logout()
    {
        Auth::logout();
        Session::forget(['name', 'otp']);

        return redirect('/')->with('success', 'You logged out successfully');
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
            $this->handleMessage($update);
        }

        return response('OK', 200);
    }

    private function handleCallbackQuery($callbackQuery)
    {
        $callbackData = $callbackQuery['data'];
        $chatId = $callbackQuery['message']['chat']['id'];
        $username = $callbackQuery['message']['chat']['username'];

        if ($callbackData === 'get_otp') {
            $otp = $this->otpService->generateOtp();
            $message = "Your OTP code is: " . $otp;
            $this->sendTelegramMessage($chatId, $message);

            OtpUser::updateOrCreate(
                ['name' => $username],
                ['phone' => $chatId, 'otp' => $otp]
            );

            $this->answerCallbackQuery($callbackQuery['id'], 'One-time password sent.');
        }
    }

    private function handleMessage($update)
    {
        if (isset($update['message'])) {
            $chatId = $update['message']['chat']['id'];
            $username = $update['message']['chat']['username'];
            $this->start($chatId, $username);
        }
    }

    public function start($chatId, $username)
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

    private function sendTelegramMessage($chatId, $message, $replyMarkup = null)
    {
        $botToken = config('otp.TELEGRAM_BOT_TOKEN');
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

    private function answerCallbackQuery($callbackQueryId, $text)
    {
        $botToken = config('otp.TELEGRAM_BOT_TOKEN');
        $apiUrl = "https://api.telegram.org/bot{$botToken}/answerCallbackQuery";

        $data = [
            'callback_query_id' => $callbackQueryId,
            'text' => $text
        ];

        $this->sendTelegramRequest($apiUrl, $data);
    }

    private function sendTelegramRequest($apiUrl, $data)
    {
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
