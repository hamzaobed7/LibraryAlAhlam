<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Remove_Frome_remaining;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Exception;

class RemoveStockService
{
    
    public function getAllOperations(): Collection
    {
        return Remove_Frome_remaining::with('book')->get();
    }

    public function handleStockOperation(array $data): Remove_Frome_remaining
    {
        return DB::transaction(function () use ($data) {
            $book = Book::findOrFail($data['book_id']);

         
            if ($data['type'] === 'destroy' && $data['remove_from_remaining']) {
                if ($book->stock < $data['quantity']) {
                   
                    throw new Exception('Quantity exceeds available stock', 400);
                }
                
                $book->decrement('stock', $data['quantity']);
            }

            
            if ($data['type'] === 'add' && $data['remove_from_remaining']) {
                $book->increment('stock', $data['quantity']);
            }

            
            return Remove_Frome_remaining::create($data);
        });
    }
}