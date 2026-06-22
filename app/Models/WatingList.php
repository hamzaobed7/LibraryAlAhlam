<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WatingList extends Model
{
    protected $table="wating_list";
    protected $fillable = ['customer_id','book_id'];

    function customer():BelongsTo{
        return $this->belongsTo(Customer::class);
    }
    function book():BelongsTo{
        return $this->belongsTo(Book::class);
    }
}
