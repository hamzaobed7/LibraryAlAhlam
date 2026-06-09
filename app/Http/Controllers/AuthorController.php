<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorRequest;
use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\AuthorService;

class AuthorController extends Controller
{
   protected $authorService;
    public function __construct(AuthorService $authorService){
        $this->authorService=$authorService;
    }
    public function index():JsonResponse
    {
        return apiSuccess("All Author",$this->authorService->getAllAuthors(),200);
    }

   
    public function store(AuthorRequest $request):JsonResponse
    {
      $data=$request->validated();
      $author=$this->authorService->createAuthor($data);
      return apiSuccess("Author Created",$author,201);  
    }

    public function show(Author $author):JsonResponse
    {
     return apiSuccess("Author get",$author,200);
    }

    public function update(AuthorRequest $request,Author $author):JsonResponse
    {
       $data=$request->validated();
       return apiSuccess("Author Updated",$this->authorService->updateAuthor($author,$data),200);   
    }

   
    public function destroy(Author $author):JsonResponse
    {
         return apiSuccess("Author Deleted",$this->authorService->deleteAuthor($author),200);     
    }
}
