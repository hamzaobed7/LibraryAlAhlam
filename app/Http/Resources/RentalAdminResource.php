<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RentalAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [
            'id'           => $this->id,
            'ISBN'         => $this->book?->ISBN ?? 'N/A',
            'title'        => $this->book?->title ?? 'N/A',
            'name' => $this->customer?->name,
            'rental_price' => $this->book?->rental_price ?? 0,
            'due_date'     => $this->due_date,
            'returned_at'  => $this->returned_at,
        ];
    }
}
