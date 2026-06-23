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
use Illuminate\Database\Eloquent\Builder;
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

    public function scopeAvailable(Builder $q):Builder
    {
      return $q->where('stock','>',0);
    }
    
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, "author_book", "book_id", "author_id");
    }

    public function removeStock():HasMany
    {
       return $this->hasMany(Remove_Frome_remaining::class, "book_id");
    }

  public function cartItems():HasMany
{
    return $this->hasMany(CartItem::class, 'book_id');
}

  public function WaitingLists():HasMany{
    return $this->hasMany(WatingList::class);
  }

  public function scopeFilter(Builder $query, array $filters)
{
    return $query->when($filters['title'] ?? null, function ($q, $title) {
            $q->where('title', 'LIKE', "%{$title}%");
        })
        ->when($filters['author'] ?? null, function ($q, $author) {
            $q->whereHas('authors', function ($authorQuery) use ($author) {
                $authorQuery->where('first_name', 'LIKE', "%{$author}%");
            });
        })
        ->when($filters['category'] ?? null, function ($q, $category) {
            $q->whereHas('category', function ($categoryQuery) use ($category) {
                $categoryQuery->where('name', 'LIKE', "%{$category}%");
            });
        })
        ->when($filters['from_date'] ?? null, function ($q, $fromDate) {
            $q->whereDate('created_at', '>=', $fromDate);
        })
        ->when($filters['to_date'] ?? null, function ($q, $toDate) {
            $q->whereDate('created_at', '<=', $toDate);
        });
}

}