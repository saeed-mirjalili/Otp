<?php
namespace Saeed\Otp\Http\Controllers;

use App\Http\Controllers\Controller;
//use App\Models\OtpUser;
//use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return redirect()->route('home')->with('success', 'you logged out successfully ');
    }

}
