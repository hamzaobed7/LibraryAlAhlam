<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'ISBN' => $this->ISBN,
            'title' => $this->title,
            'rental_price' => $this->rental_price,
            'deposit' => $this->deposit,
            'pages' => $this->pages,
            'default_borrow_days' => $this->default_borrow_days,
            'total_copies' => $this->total_copies,
            'stock' => $this->stock,
            'published_at' => $this->published_at,
            'category' => $this->whenLoaded('category'), 
            'authors' => AuthorResource::collection($this->whenLoaded('authors')), 
        ];
    }
}