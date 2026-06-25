<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookPolicy
{
   
    public function viewAny(User $user): bool
    {
        return false;
    }

   
    public function view(User $user, Book $book): bool
    {
        return false;
    }

  
    public function create(User $user): bool
    {
        return $user->type=='admin';
    }


    public function update(User $user, Book $book): bool
    {
        return $user->type=='admin';
    }

   
    public function delete(User $user, Book $book): bool
    {
        return $user->type==='admin';
    }

   
   
}
