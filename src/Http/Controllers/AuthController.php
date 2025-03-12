<?php
namespace Saeed\Otp\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Saeed\Otp\Models\OtpUser;
use Saeed\Otp\WhatsappOtpService;
use Saeed\Otp\TelegramOtpService;

class AuthController extends Controller
{
    protected $WhatsappOtpService;


    public function __construct(WhatsappOtpService $WhatsappOtpService)
    {
        $this->WhatsappOtpService = $WhatsappOtpService;
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

        $otp = $this->WhatsappOtpService->generateOtp();

        if ($request->has('phone')) {
            $phone = $request->phone;
        } else {
            $user = OtpUser::where('name', $request->name)->first();
            if (!$user) {
                return redirect()->back()->with('error', 'OtpUser not found');
            }
            $phone = $user->phone;
        }

        $this->WhatsappOtpService->sendOtp($otp, $phone);

        session(['otp' => $otp, 'phone' => $phone, 'name' => $validData['name']]);

        return redirect()->back()->with('success', 'OTP sent successfully');
    }

    public function verifyOtp(Request $request)
    {

        $request->validate(['otp' => 'required|integer']);
        if ($request->otp == session('otp')) {
            $user = OtpUser::firstOrCreate(
                ['name' => session('name')],
                ['phone' => session('phone')]
            );

            Auth::login($user);
            return redirect()->route('home');
        }
        return redirect()->back()->with('error', 'Invalid Otp');
    }



    public function logout()
    {
        Auth::logout();
        Session::forget(['name', 'otp']);

        return redirect('/')->with('success', 'You logged out successfully');
    }
}
