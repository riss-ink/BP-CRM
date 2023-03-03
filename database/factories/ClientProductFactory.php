<?php

use Faker\Generator as Faker;

$factory->define(App\ClientProduct::class, function (Faker $faker) {
    return [
        'client_id' => App\Client::all()->random()->id,
        'product_id' => App\Product::all()->random()->id
    ];
});
