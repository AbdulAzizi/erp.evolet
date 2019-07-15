<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Field::class, function (Faker $faker) {

    $fieldName = $faker->word;
    
    return [
        'label' => $fieldName,
        'name' => $fieldName
    ];
});
