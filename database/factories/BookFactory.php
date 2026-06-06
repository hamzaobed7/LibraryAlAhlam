<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ISBN'                => $this->faker->isbn13(),
            'title'               => $this->faker->sentence(3),
            'rental_price'        => $this->faker->randomFloat(2, 1, 100),
            'deposit'             => $this->faker->randomFloat(2, 10, 200),
            'pages'               => $this->faker->numberBetween(50, 1000),
            'default_borrow_days' => $this->faker->numberBetween(7, 30),
            'total_copies'        => $this->faker->numberBetween(1, 20),
            'stock'               => $this->faker->numberBetween(0, 20),
            'published_at'        => $this->faker->date(),
            'cover'               => $this->faker->imageUrl(200, 300, 'books', true),
            'category_id'         => \App\Models\Category::factory(),
        ];
    }
}
