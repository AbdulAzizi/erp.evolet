<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use App\Process;
use App\Tether;

$factory->define(Tether::class, function (Faker $faker) {
    return [
        'from_process_id' => getRandomId(Process::all()),
        'to_process_id' => getRandomId(Process::all()),
        'form_id' => getRandomId(Process::all()),
        'action_text' => $faker->word
    ];
});
