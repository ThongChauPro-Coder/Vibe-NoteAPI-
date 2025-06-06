<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\OTPMail;
use Illuminate\Support\Facades\Mail;

class OTPController extends Controller
{
    public function sendOTP(Request $request)
    {
        $otp = rand(100000, 999999);
        $email = $request->input('email');

        session(['otp' => $otp]);

        Mail::to($email)->send(new OTPMail($otp));

        return response()->json([
            'message' => 'OTP has been sent to your email'
        ]);
        
    }

    public function verifyOTP(Request $request)
    {
        $inputOtp = $request->input('otp');
        $storedOtp = session('otp');

        if ($inputOtp == $storedOtp) {
            return response()->json(['message' => 'OTP is valid!']);
        } else {
            return response()->json(['message' => 'Invalid OTP.'], 400);
        }
    }
}
