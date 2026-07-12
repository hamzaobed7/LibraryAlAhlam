<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RentalResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title'=> $this->book->title,
            'ISBN'=> $this->book->ISBN,
            'cover'=>$this->book->cover,
            'rental_price'=> $this->book->rental_price,
            'due_date'=> $this->due_date,
            'returned_at'=> $this->returned_at,
        ];
    }
}
