<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use \App\Models\Order\orderReoCategory;
use Faker\Generator as Faker;

$factory->define(orderReoCategory::class, function (Faker $faker) {
    return [
        //
        'name'=>$faker->name,
        'description'=>$faker->description,
    ];
});
