<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    function createOtp(User $user)
    {

        $otpCode = random_int(100000, 999999);

        DB::transaction(function () use ($user , $otpCode){

            $user->otp()->delete();
            $user->otp()->create([
                'otp_hash' => $otpCode,
                'expires_at' => now()->addMinutes(15),
            ]);
        });

        Mail::raw(
            "Your verification code is: $otpCode",
            function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('OTP Verification');
            }
        );
    }   
}