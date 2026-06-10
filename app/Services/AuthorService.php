<?php

namespace App\Services;

use App\Models\Author;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class AuthorService
{
    public function getAllAuthors(): Collection
    {
        return Author::all();
    }

    
    public function createAuthor(array $data): Author
    {
        return Author::create($data);
    }

    public function updateAuthor(Author $author, array $data): Author
    {
        $author->update($data);
        return $author;
    }

    
    public function deleteAuthor(Author $author): ?bool
    {
        return $author->delete();
    }

   
}