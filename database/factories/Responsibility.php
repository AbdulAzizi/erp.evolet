<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Responsibility;
use App\Position;
use Faker\Generator as Faker;

$factory->define(Responsibility::class, function (Faker $faker) {
    return [
        'text' => $faker->realText(50),
        'position_id' => getRandomId(Position::all()),
    ];
});
