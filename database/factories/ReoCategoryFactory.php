<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use \App\Models\Order\orderReoCategory;
use Faker\Generator as Faker;

$factory->define(\App\Models\Order\reoCategories::class, function (Faker $faker) {
    return [
        //
        'name'=>$faker->name,
//        'description'=>$faker->description,
    ];
});
