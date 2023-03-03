<?php

use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'product_category_id' => App\ProductCategory::all()->random()->id,
        'name' => $faker->name,
    ];
});
