<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Project;
use Faker\Generator as Faker;
use App\Country;
use App\Division;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'pc_id' => getRandomId( Division::withDepth()->having('depth','=', 4)->get() ),
        'country_id' => getRandomId(Country::all()),
    ];
});
