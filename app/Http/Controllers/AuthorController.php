<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorRequest;
use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\AuthorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthorController extends Controller
{
  protected $authorService;
  public function __construct(AuthorService $authorService)
  {
    $this->authorService = $authorService;
  }
  public function index(): JsonResponse
  {

    $authors = $this->authorService->getAllAuthors();
    return apiSuccess("تم جلب جميع المؤلفين", $authors, 200);
  }


  public function store(AuthorRequest $request): JsonResponse
  {
    $data = $request->validated();
    $author = $this->authorService->createAuthor($data);
    return apiSuccess("Author Created", $author, 201);
  }

  public function show(Author $author): JsonResponse
  {
    return apiSuccess("Author get", $author, 200);
  }

  public function update(AuthorRequest $request, Author $author): JsonResponse
  {
    $data = $request->validated();
    return apiSuccess("Author Updated", $this->authorService->updateAuthor($author, $data), 200);
  }


  public function destroy(Author $author): JsonResponse
  {
    Gate::authorize('delete', $author);

    return apiSuccess("Author Deleted", $this->authorService->deleteAuthor($author), 200);
  }


  public function AuthorCount()
  {
    $author = Author::count();
    return $author;
  }
  public function HasNoBook()
  {
    $author = Author::has('books', "=", 0)->count();
    return $author;
  }

  public function DeleteManyAuthor(Request $request): JsonResponse
  {
    $request->validate([
      'ids' => 'required|array',
      'ids.*' => 'exists:authors,id'
    ]);
    Gate::authorize('delete', Author::class);
    $this->authorService->deleteMultipleAuthors($request->input('ids'));
    return apiSuccess("تم الحذف بنجاح", code: 200);
  }
}
