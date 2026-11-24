<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'author' => fake()->name(),
            'description' => fake()->optional()->paragraph(),
            'published_year' => fake()->numberBetween(1900, (int) date('Y')),
            'category_id' => Category::factory(),
        ];
    }
}
