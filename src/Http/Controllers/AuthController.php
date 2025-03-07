<?php
namespace Saeed\Otp\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
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
        if ($request->has('phone')){
            $this->otpService->sendOtp($otp, $request->phone);
            session(['otp' => $otp,'phone' => $request->phone, 'name' => $validData['name']]);
        }else{
            $user = OtpUser::where('name', $request->name)->first();
            if (is_null($user)){
                return redirect()->back()->with('error', 'OtpUser not found');
            }
            $this->otpService->sendOtp($otp, $user->phone);
            session(['otp' => $otp,'phone' => $user->phone, 'name' => $validData['name']]);
        }
        return redirect()->back()->with('success', 'otp sent successfully');
    }

    public function verifyOtp(Request $request)
    {
        Log::info(\session('otp'));
        $request->validate(['otp' => 'required|integer']);
        if ($request->otp == session('otp')) {
            $user = OtpUser::firstOrCreate(
                ['name' => session('name')],
                ['phone' => session('phone')]
            );

            Auth::login($user);
            Session::forget('otp');
            return redirect()->route('home');
        }
        return redirect()->back()->with('error', 'Invalid Otp');
    }


    public function logout()
    {
        Auth::logout();
        Session::forget('name','phone');
        return redirect('/')->with('success', 'you logged out successfully ');
    }







    public function telegramHook(Request $request)
    {
        $update = $request->all();

        if (isset($update['callback_query'])) {
            $this->handleCallbackQuery($update['callback_query']);
        } else {
            $this->handleMessage($update);
        }

        return response('OK', 200);
    }

    private function handleCallbackQuery($callbackQuery)
    {

        $callbackData = $callbackQuery['data'];
        $chat_id = $callbackQuery['message']['chat']['id'];
        $name = $callbackQuery['message']['chat']['username'];

        if ($callbackData === 'get_otp') {
            $otp = $this->otpService->generateOtp();
            $message = "Your OTP code is: " . $otp;
            $this->sendTelegramMessage($chat_id, $message);

            session(['otp' => $otp, 'phone' => 'bot', 'name' => $name]);
            $this->answerCallbackQuery($callbackQuery['id'], 'One-time password sent.');
        }
    }

    private function handleMessage($update)
    {
        if (isset($update['message'])) {
            $chat_id = $update['message']['chat']['id'];
            $name = $update['message']['chat']['username'];
            $this->start($chat_id, $name);
        }
    }

    public function start($chat_id, $name)
    {
        $keyboard = [
            [
                ['text' => 'Receive a one-time password', 'callback_data' => 'get_otp']
            ]
        ];

        $replyMarkup = json_encode([
            'inline_keyboard' => $keyboard
        ]);

        $botToken = config('otp.TELEGRAM_BOT_TOKEN');
        $apiUrl = "https://api.telegram.org/bot{$botToken}/sendMessage";

        $data = [
            'chat_id' => $chat_id,
            'text' => 'To receive a one-time password, click the button below:',
            'reply_markup' => $replyMarkup
        ];

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return response('OK', 200);
    }

    private function sendTelegramMessage($chat_id, $message)
    {
        $botToken = config('otp.TELEGRAM_BOT_TOKEN');
        $apiUrl = "https://api.telegram.org/bot{$botToken}/sendMessage";

        $data = [
            'chat_id' => $chat_id,
            'text' => $message
        ];

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
    }

    private function answerCallbackQuery($callbackQueryId, $text)
    {
        $botToken = config('otp.TELEGRAM_BOT_TOKEN');
        $apiUrl = "https://api.telegram.org/bot{$botToken}/answerCallbackQuery";

        $data = [
            'callback_query_id' => $callbackQueryId,
            'text' => $text
        ];

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
    }
}
