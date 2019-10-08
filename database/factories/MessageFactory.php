<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Message;
use Faker\Generator as Faker;

$factory->define(Message::class, function (Faker $faker) {
    return [
        'body' => $faker->realText($maxNbChars = 150, $indexSize = 2),
        'user_id' => rand(1, 20)
    ];
});
