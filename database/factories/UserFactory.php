<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use App\Position;
use App\Responsibility;
use App\Division;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    $surname = $faker->unique()->lastName;
    return [
        'name' => $faker->firstName,
        'surname' => $surname,
        'position_id' => getRandomId(Position::class, [1]),
        'responsibility_id' => getRandomId(Responsibility::class, [4]),
        'division_id' => getRandomId(Division::class, Division::withDepth()->having('depth','!=', 3)->pluck('id')->toArray()),
        'email' =>  $surname . '@admin.com',
        'email_verified_at' => now(),
        'password' => bcrypt('admin'),
        'remember_token' => Str::random(10),
    ];
});

function getRandomId($class, $exceptions = [])
{
    return $class::all()->except($exceptions)->random()->id;
}