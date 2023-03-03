<?php

use Faker\Generator as Faker;

$factory->define(App\FieldGroup::class, function (Faker $faker) {
    return [
        'name' => $faker->word
    ];
});
