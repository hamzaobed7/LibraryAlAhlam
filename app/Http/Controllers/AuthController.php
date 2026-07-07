<?php

namespace App\Http\Controllers;

use App\Events\ActivateAccount;
use App\Events\AuthonticationEvent;
use App\Events\CreateOtp;
use App\Models\User;
use App\Models\Otp;
use App\Notifications\OtpNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests\CustomerRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    protected CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function signup(CustomerRequest $request)
    {
        $data = $request->validated();
        $customer = $this->customerService->addCustomer($data, $request->file('cover'));
        $user = $customer->user;
        $otp_code = rand(100000, 999999);
        CreateOtp::dispatch($user, $otp_code);
        Otp::where('user_id', $customer->user_id)->delete();
        Otp::create([
            'user_id' => $customer->user_id,
            'otp_hash' => Hash::make($otp_code),
            'expires_at' => now()->addMinute(5),
            'attempts' => 0
        ]);
        return apiSuccess("تم انشاء زبون غير مفعل", $customer, code: 201);
    }

    public function verify_otp(VerifyOtpRequest $request): JsonResponse
    {
        $data = $request->validated();

        $otp = Otp::where('user_id', $data['user_id'])->first();

        if (!$otp) {
            return apiFail("the otp is not exist", 404);
        }

        if ($otp->expires_at < now()) {
            return apiFail("the time is end", 400);
        }

        if ($otp->attempts >= 5) {
            return apiFail("the count of attempts is finished", 429);
        }

        if (!Hash::check($data['otp_hash'], $otp->otp_hash)) {
            $otp->increment('attempts');
            return apiFail("your code is wrong", 400);
        }
        $user = $otp->user;
        $user->update([
            'email_verified_at' => now()
        ]);
        $otp->delete();
        ActivateAccount::dispatch($user);
        return apiSuccess("تم تفعيل الحساب بنجاح");
    }


    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return apiFail('Invalid email or password', [], 401);
        }
        if (!$user->email_verified_at) {
            return apiFail('The email is not activated', [], 401);
        }
        // $token = $user->createToken('auth_token')->plainTextToken;
        AuthonticationEvent::dispatch($user);
        //New
        Auth::login($user);
        $request->session()->regenerate();
        return apiSuccess('Login successful', [
            'user' => $user,
        ], 200);
    }

    public function user(Request $request): JsonResponse
    {
        $user = Auth::user();
        return apiSuccess('Authenticated user retrieved successfully', [
            'user' => $user
        ], 200);
    }




    public function logout(Request $request): JsonResponse
    {
        // if ($request->user() && $request->user()->currentAccessToken()) {
        //     $request->user()->currentAccessToken()->delete();
        // }
        // return apiSuccess('Logout successful', [], 200);

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return apiSuccess('Logout successful');
    }
}
