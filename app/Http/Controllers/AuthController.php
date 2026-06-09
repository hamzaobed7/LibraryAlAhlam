<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Otp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\AuthRequest;
class AuthController extends Controller
{
    public function signup(AuthRequest $request): JsonResponse
    {
        
        $data = $request->all(); 
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        return apiSuccess('User registered successfully', [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->all();
        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return apiFail('Invalid email or password', [], 401);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return apiSuccess('Login successful', [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }

    public function user(Request $request): JsonResponse
    {
        return apiSuccess('Authenticated user retrieved successfully', [
            'user' => $request->user()
        ], 200);    
    }

    public function logout(Request $request): JsonResponse
    {
        if ($request->user() && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }
        return apiSuccess('Logout successful', [], 200);
    }

    public function send_otp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        $data = $validator->validated();
        $otp_code = rand(100000, 999999);        
        Mail::raw("Your verification code is: {$otp_code}", function ($message) use ($data) {
            $message->to($data['email'])->subject('OTP Verification');
        });
        Otp::where('email', $data['email'])->delete();
        Otp::create([
            'email' => $data['email'],
            'otp_code' => $otp_code,
            'expires_at' => now()->addMinutes(5)
        ]);
        return apiSuccess('OTP sent successfully', [], 200);
    }

    public function verify_otp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp_code' => 'required|digits:6'
        ]);
        if ($validator->fails()) {
            return apiFail('Validation failed', $validator->errors(), 422);
        }
        $data = $validator->validated();
        $otp = Otp::where('email', $data['email'])
            ->where('otp_code', $data['otp_code'])
            ->where('expires_at', '>', now())
            ->first();
        if (!$otp) {
            User::where('email', $data['email'])->delete();
            return apiFail('Invalid or expired OTP', [], 400);
        }      
        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            return apiFail('User account not found', [], 444);
           }
        $token = $user->createToken('auth_token')->plainTextToken;
        $otp->delete();
        return apiSuccess('OTP verified successfully', [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 200);    
    }
}