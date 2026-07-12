<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaypalService
{
    public function getAccessToken(): string
    {
        $response = Http::asForm()
            ->withBasicAuth(
                config('services.paypal.client_id'),
                config('services.paypal.secret')
            )
            ->post(
                config('services.paypal.base_url') . '/v1/oauth2/token',
                [
                    'grant_type' => 'client_credentials',
                ]
            );

        if (!$response->successful()) {
            throw new \Exception('Failed to get PayPal Access Token');
        }

        return $response->json('access_token');
    }

    public function createOrder(float $amount): array
    {
        $accessToken = $this->getAccessToken();
        $response = Http::withToken($accessToken)
            ->post(config('services.paypal.base_url') . '/v2/checkout/orders', [
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => number_format($amount, 2, '.', '')
                        ]
                    ]
                ]
            ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to create PayPal Order: ' . $response->body());
        }

        return $response->json();
    }



    public function captureOrder(string $paypalOrderId): array
{
    $accessToken = $this->getAccessToken();
    $response = Http::withToken($accessToken)
        ->withBody('{}', 'application/json')
        ->post(config('services.paypal.base_url') . "/v2/checkout/orders/{$paypalOrderId}/capture");

    if (!$response->successful()) {
        throw new \Exception('Failed to capture PayPal Order: ' . $response->body());
    }

    return $response->json();
}
}
