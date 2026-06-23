<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class  BillItemResource   extends JsonResource{


 public function toArray(Request $request): array
    {
        return [
            "book_name"=>$this->book->title,
           "rental_price"=>$this->rental_price,
           "deposit_amount"=>$this->deposit_amount,
           "fine_amount"=>$this->fine_amount
           
        ];
    }

}