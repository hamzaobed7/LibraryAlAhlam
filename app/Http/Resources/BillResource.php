<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class  BillResource   extends JsonResource{


 public function toArray(Request $request): array
    {
        return [
           "total_amount"=>$this->total_amount,
           "status"=>$this->status,
           "date"=>$this->created_at,
           "bill_items"=>BillItemResource::collection($this->whenLoaded('bill_items')),
        ];
    }

}