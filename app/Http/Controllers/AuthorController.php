<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorRequest;
use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():JsonResponse
    {
        return apiSuccess("All Author",Author::all(),200);
    }

   
    public function store(AuthorRequest $request):JsonResponse
    {
      $data=$request->validated();
      $author=Author::create($data);
      return apiSuccess("Author Created",$author,201);
    }

    public function show(Author $author):JsonResponse
    {
     return apiSuccess("Author get",$author,200);
    }

    public function update(AuthorRequest $request,Author $author):JsonResponse
    {
       $data=$request->validated();
       $author->update($data);
        return apiSuccess("Updated",$author,200);
    }

   
    public function destroy(Author $author):JsonResponse
    {
         $author->delete();
         return apiSuccess("deleted");
    }
}
