<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bill extends Model
{
    protected $fillable = ['customer_id', 'total_amount', 'status'];

    function bill_items(): HasMany
    {
        return $this->hasMany(BillItem::class);
    }
    function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
