<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Psy\Util\Json;
use Illuminate\Http\JsonResponse; 
use App\Models\Remove_Frome_remaining;  
use App\Models\Book;
class Remove_Frome_remainingController extends Controller
{
    public function index():JsonResponse
    {
        return apiSuccess("Remove_Frome_remaining ",Remove_Frome_remaining::all(),200);
    }
public function store(Request $request)
{
    $data = $request->all();

    $book = Book::findOrFail($data['book_id']);

    if (
        $data['type'] == 'destroy' &&
        $data['remove_from_remaining'] &&
        $book->stock < $data['quantity']
    ) {
        return apiFail('Quantity exceeds available stock', 400);
    }

    if (
        $data['type'] == 'add' &&
        $data['remove_from_remaining']
    ) {
        $book->increment('stock', $data['quantity']);
    }

    if (
        $data['type'] == 'destroy' &&
        $data['remove_from_remaining']
    ) {
        $book->decrement('stock', $data['quantity']);
    }

    Remove_Frome_remaining::create($data);

    return apiSuccess(
        'Operation created successfully',
        null,
        201
    );
}

   
}
