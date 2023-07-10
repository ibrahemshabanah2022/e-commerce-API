<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $categories = Category::all()->pluck('id')->toArray();

        for ($i = 0; $i < 10; $i++) {
            Product::create([
                'title' => $faker->title,
                'price' => $faker->randomNumber(5),
                'description' => $faker->sentence(10),
                'image' => $faker->imageUrl(640, 480, 'jpg'),
                'category_id' => $faker->randomElement($categories),


            ]);
        }
    }
}
