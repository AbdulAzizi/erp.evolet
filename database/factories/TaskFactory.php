<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Task;
use Faker\Generator as Faker;
use App\User;

function getRandomId($collection, $exceptions = [])
{
    return $collection->except($exceptions)->random()->id;
}

$factory->define(Task::class, function (Faker $faker) {
    return [
        'title' => $faker->words($nb = 3, $asText = true),
        'description' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'planned_time' => $faker->numberBetween(100000,9999999999),
        'priority' => $faker->numberBetween($min = 0, $max = 2),
        'deadline' => $faker->dateTimeBetween($startDate = 'now', $endDate = '2 years', $timezone = null),
        'from_id' => getRandomId(User::all()),
        'from_type' => 'App\User',
        'created_at' => $faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now', $timezone = null),
    ];
});
