<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Author;
class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = Author::all();
        if ($authors->isEmpty()) {
            $this->command->warn(' Fill the Author data');
            return;
        }

        Book::factory() 
            ->count(50)
            ->create()
            ->each(function ($book) use ($authors) {
                $randomAuthorIds = $authors->random(rand(1, 3))->pluck('id')->toArray();
                $book->authors()->attach($randomAuthorIds);
            });
    }
}
