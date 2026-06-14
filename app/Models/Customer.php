<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Customer extends Model
{
    protected $table='customers';
    protected $fillable = ['name','gender','DOB','cover','phone','lang','user_id'];
      public $timestamps = false;
    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }
}
