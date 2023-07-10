<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {
            Category::create([
                'name' => $faker->word(5),
                'image' => $faker->imageUrl(),
            ]);
        }
    }
}
