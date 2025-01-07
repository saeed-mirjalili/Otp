<?php
namespace Saeed\Otp;

class OtpService
{
protected $Instance;
protected $Token;

public function __construct()
{
    $this->Instance = config('otp.WhatsApp_Intance');
    $this->Token = config('otp.WhatsApp_Token');
}

public function generateOtp($length = 6)
{
    return rand(pow(10, $length - 1), pow(10, $length) - 1);
}

public function sendOtp($otp, $mobileNumber)
{
    $message = "Your OTP code is: " . $otp;

    $params = array(
        'token' => $this->Token,
        'to' => $mobileNumber,
        'body' => $message
    );

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.ultramsg.com/$this->Instance/messages/chat",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => http_build_query($params),
        CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo $response;
    }
}
}
