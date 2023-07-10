<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->sentence,
        'image' => $faker->imageUrl,
    ];
});

$factory->state(Category::class, 'parent', [
    'parent_id' => null,
]);
