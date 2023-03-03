<?php

use Faker\Generator as Faker;

$factory->define(App\Definition::class, function (Faker $faker) {
    $groupCount = App\FieldGroup::all()->count();
    $fields = [
         'string',
        'text',
        'currency',
        'int',
        'json'
    ];
    return [
        'field_group_id' => rand(0, $groupCount),
        'name' => $faker->word,
        'field_type' => $faker->randomElement($fields),
        'type' => 'product_category'
    ];
});
