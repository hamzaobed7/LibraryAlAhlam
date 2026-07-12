<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    use Prunable;
    protected $table='customers';
    protected $fillable = ['name','gender','DOB','cover','phone','lang','user_id'];
    
    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }
    public function book_request():HasMany{
        return $this->hasMany(Book_request::class);
    }
     public function WaitingLists():HasMany{
    return $this->hasMany(WatingList::class);
  }

public function rentals():HasMany{
    return $this->hasMany(Rental::class);
  }
  public function cart():HasMany{
    return $this->hasMany(CartItem::class);
  }

  public function bills():HasMany{
    return $this->hasMany(Bill::class);
  }

    public function prunable(): Builder
{
    return static::whereHas('user', function ($q) {
        $q->whereNull('email_verified_at');
    })->where('created_at', '<', now()->subDay());
} 

}
