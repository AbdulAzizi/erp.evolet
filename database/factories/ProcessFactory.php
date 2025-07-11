<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Process;
use Faker\Generator as Faker;

$factory->define(Process::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence($nbWords = 4, $variableNbWords = true)
    ];
});
