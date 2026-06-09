<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $factory = CategoryFactory::class;
    protected $table = 'categories';
    protected $primaryKey = 'id';
   protected $fillable = ['name','description'];
 public $timestamps = false;

public function books():HasMany{
  return $this->hasMany(Book::class);
}

}
