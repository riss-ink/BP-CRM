<?php

use Faker\Generator as Faker;

$factory->define(App\Team::class, function (Faker $faker) {
    return [
        'name' => $faker->randomElement(['The Spirit Ferrets',
            'The Mad Octopi',
            'The Wicked Rovers',
            'The Pure Orcas',
            'The Handsome Warriors',
            'The Flawless Runners',
            'The Nimble Orcas',
            'The Defiant Werewolves',
            'The Dapper Orcas',
            'The Big Porcupines',
        ])
    ];
});
