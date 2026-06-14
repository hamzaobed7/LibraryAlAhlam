<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Otp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\SignupRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Services\CustomerService;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    protected CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function signup(SignupRequest $request): JsonResponse
    {
        $data = $request->validated();  
        $otp_code = rand(100000, 999999);        
        
        Mail::raw("Your verification code is: {$otp_code}", function ($message) use ($data) {
            $message->to($data['email'])->subject('OTP Verification');
        });

        Otp::where('email', $data['email'])->delete();
        Otp::create([
            'email'      => $data['email'],
            'otp_code'   => $otp_code,
            'expires_at' => now()->addMinutes(5)
        ]);
        
        return apiSuccess('OTP sent successfully. Please verify to complete registration.', [], 200);
    }

    public function verify_otp(VerifyOtpRequest $request): JsonResponse
    {
        $data = $request->validated();

        $otp = Otp::where('email', $data['email'])
            ->where('otp_code', $data['otp_code'])
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            return apiFail('Invalid or expired OTP', [], 400);
        }      

        $user = null;
        $token = null;

        DB::transaction(function() use ($data, $request, &$user, &$token) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $data['user_id'] = $user->id;
            
            $this->customerService->addCustomer($data, $request->file('cover'));
            
            $token = $user->createToken('auth_token')->plainTextToken;
        });

       
        $otp->delete();

        return apiSuccess('User registered and verified successfully', [
            'user'         => $user,
            'access_token' => $token,
            'token_type'   => 'Bearer'
        ], 201);    
    }

    public function login(AuthRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();
        
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return apiFail('Invalid email or password', [], 401);
        }
        
        $token = $user->createToken('auth_token')->plainTextToken;
        return apiSuccess('Login successful', [
            'user'         => $user,
            'access_token' => $token,
            'token_type'   => 'Bearer'
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
}