<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\History;
use App\Task;
use App\User;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(History::class, function (Faker $faker) {
    return [
        'user_id' => getRandomId(User::all()),
        'previous_id' => null,
        'description' => $faker->text(500),
        'happened_at' => Carbon::now()->toDateTimeString(),
        'happend_with_id' => getRandomId(Task::all()),
        'happend_with_type' => Task::class
    ];
});
