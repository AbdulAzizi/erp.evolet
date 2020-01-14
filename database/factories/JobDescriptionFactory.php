<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\JobDescription;
use App\Responsibility;
use Faker\Generator as Faker;

$factory->define(JobDescription::class, function (Faker $faker) {
    return [
        'text' => $faker->realText(50),
        'responsibility_id' => getRandomId(Responsibility::all()),
        'level' => 1
    ];
});
