<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentResourse;
use App\Models\Bill;
use App\Models\Payment;
use App\Models\Rental;
use App\Services\PaypalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected $paypalService;


    public function __construct(PaypalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'bill_id' => 'required|exists:bills,id',
            'method' => 'required|in:cash,online',
        ]);

        $bill = Bill::findOrFail($request->bill_id);
        
        if($countOfRentals >= 3) {
            return apiFail('You have reached the maximum number of active rentals. Please return a book before making a cash payment.', 400);
        }
        if (
            Payment::where('bill_id', $bill->id)
            ->where('status', 'pending')
            ->exists()
        ) {
            return apiFail('A pending payment already exists.', 409);
        }

        if ($bill->status !== 'pinding_payment') {
            return apiFail('Payment can only be made for pending bills.', 400);
        }
        try {
            $paypalOrder = $this->paypalService->createOrder($bill->total_amount);
            $payment = Payment::create([
                'bill_id' => $bill->id,
                'amount'  => $bill->total_amount,
                'method'  => 'online',
                'type'    => 'payment',
                'status'  => 'pending',
            ]);
            return apiSuccess('PayPal order initialized successfully.', [
                'payment' => $payment,
                'paypal_order_id' => $paypalOrder['id']
            ], 201);
        } catch (\Exception $e) {
            return apiFail('PayPal Initialization Failed: ' . $e->getMessage(), 500);
        }
    }


    public function capturePayment(Request $request): JsonResponse
    {
        $request->validate([
            'paypal_order_id' => 'required|string',
            'bill_id' => 'required|exists:bills,id'
        ]);
        $paypalResult = $this->paypalService->captureOrder($request->paypal_order_id);
        if (isset($paypalResult['status']) && $paypalResult['status'] === 'COMPLETED') {
            $bill = Bill::findOrFail($request->bill_id);

            $payment = Payment::where('bill_id', $bill->id)->where('status', 'pending')->first();
            $payment->update([
                'bill_id' => $bill->id,
                'amount'  => $bill->total_amount,
                'method'  => 'online',
                'type'    => 'payment',
                'status'  => 'completed',
                'paid_at' => now(),
            ]);
            return apiSuccess('Payment captured and rentals created successfully.', null);
        }
        return apiFail('PayPal order status is not completed.', 400);
    }
}
