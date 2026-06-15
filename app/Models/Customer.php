<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Builder;
class Customer extends Model
{
    use Prunable;
    protected $table='customers';
    protected $fillable = ['name','gender','DOB','cover','phone','lang','user_id'];
    
    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function prunable(): Builder
{
    return static::whereHas('user', function ($q) {
        $q->whereNull('email_verified_at');
    })->where('created_at', '<', now()->subDay());
}
}
