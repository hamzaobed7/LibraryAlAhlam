<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WaitListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'Book Name'=>$this->whenLoaded('book',$this->book->title),
            'customer Name'=>$this->whenLoaded('customer',$this->customer->name),
            'creared_at'=>$this->created_at
        ];
    }
}
