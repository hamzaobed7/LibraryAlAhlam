<?php

namespace App\Http\Controllers;

use App\Http\Requests\Remove_Frome_remainingRequest;
use Illuminate\Http\Request;
use Psy\Util\Json;
use Illuminate\Http\JsonResponse;
use App\Models\Remove_Frome_remaining;
use App\Models\Book;
use App\Observers\OpreationOnStockObserver;
use Illuminate\Support\Facades\Auth;

class Remove_Frome_remainingController extends Controller
{
    public function index(): JsonResponse
    {
        return apiSuccess("Remove_Frome_remaining ", Remove_Frome_remaining::all(), 200);
    }
    public function store(Remove_Frome_remainingRequest $request)
    {
      if(Auth::user()->can('create',Remove_Frome_remaining::class)){
         $data = $request->validated();
        Remove_Frome_remaining::create($data);
        return apiSuccess('Operation created successfully', null, 201);
      }
      return apiFail("ليس لديك صلاحيات",code:403);
       
    }

    function theOperationAdd()
    {
        $author = Remove_Frome_remaining::all()->where('type', "LIKE", "add")->sum('quantity');
        return $author;
    }
}
