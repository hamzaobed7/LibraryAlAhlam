<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book_request extends Model
{
   protected $fillable = ['book_title','author_name','status','customer_id'];

   public function customer():BelongsTo{
    return $this->belongsTo(Customer::class);
   }
}
