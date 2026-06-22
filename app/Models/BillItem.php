<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillItem extends Model
{
    protected $fillable = ['bill_id','book_id','rental_price','deposit_amount','fine_amount'];

    function bill():BelongsTo{
        return $this->belongsTo(Bill::class);
    }
}
