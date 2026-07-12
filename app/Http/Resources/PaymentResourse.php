<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResourse extends JsonResource
{
    public function toArray($request)
    {
        return [

            'payment_id' => $this->id,

            'bill_id' => $this->bill_id,

            'amount' => $this->amount,

            'method' => $this->method,

            'status' => $this->status,

        ];
    }
}
