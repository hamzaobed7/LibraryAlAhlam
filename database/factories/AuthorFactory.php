<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Author>
 */
class AuthorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "first_name"=>$this->faker->firstName(),
            "last_name"=>$this->faker->lastName(),
            "email"=>$this->faker->email(),
            "gender"=>$this->faker->randomElement(['Male', 'Female']),
            "birth-date"=>$this->faker->date(),
            "bio"=>$this->faker->text()
        ];
    }
}
