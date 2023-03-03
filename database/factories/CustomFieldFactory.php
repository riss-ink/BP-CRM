<?php

use Faker\Generator as Faker;

$factory->define(App\CustomField::class, function (Faker $faker) {
    return [
        'definition_id' => App\Definition::all()->random()->id,
        
    ];
});
