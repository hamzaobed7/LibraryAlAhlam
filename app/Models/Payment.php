<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'bill_id',
        'amount',
        'method',
        'type',
        'status',
        'paid_at',
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }
}
