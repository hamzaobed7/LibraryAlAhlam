<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\CategoryFactory;
class Category extends Model
{
    use HasFactory;

    protected $factory = CategoryFactory::class;
    protected $table = 'categories';
    protected $primaryKey = 'id';
   protected $fillable = ['name','description'];
 public $timestamps = false;
}
