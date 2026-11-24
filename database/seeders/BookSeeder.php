<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        if (Book::count() > 0) {
            return;
        }
        $categoryIds = Category::pluck('id');

                Book::factory()
                    ->count(50)
                    ->create(['category_id' => fn () => $categoryIds->random()]);
            }
}
