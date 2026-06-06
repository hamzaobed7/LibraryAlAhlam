<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Category;
use App\Models\Author;
use App\Models\RemoveStock;
class Book extends Model
{
    use HasFactory;
    protected $table = "books";
    public $timestamps = false;
    protected $fillable = [
        "ISBN", "title", "rental_price", "deposit", "pages", 
        "default_borrow_days", "total_copies", "stock", 
        "published_at", "cover", "category_id"
    ];

   
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, "category_id");
    }

    
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, "author_book", "book_id", "author_id");
    }

    public function removeStock():HasMany
    {
       return $this->hasMany(Remove_Frome_remaining::class, "book_id");
    }
}