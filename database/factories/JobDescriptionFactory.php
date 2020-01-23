<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\JobDescription;
use App\Position;
use Faker\Generator as Faker;

$factory->define(JobDescription::class, function (Faker $faker) {
    return [
        'text' => $faker->realText(50),
        'position_id' => getRandomId(Position::all()),
        'level' => 1
    ];
});
