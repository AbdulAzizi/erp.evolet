<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Employee;
use Faker\Generator as Faker;
use App\Position;
use App\Responsibility;
use App\Division;

$factory->define(Employee::class, function (Faker $faker) {
    return [
        'user_id' => function (){
            return factory(App\User::class)->create()->id;
        },
        'position_id' => getRandomId(Position::class, [1]),
        'responsibility_id' => getRandomId(Responsibility::class),
        'division_id' => getRandomId(Division::class, Division::withDepth()->having('depth','!=', 3)->pluck('id')->toArray())
    ];
});

function getRandomId($class, $exceptions = [])
{
    return $class::all()->except($exceptions)->random()->id;
}