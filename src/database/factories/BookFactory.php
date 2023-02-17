<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Book::class, function (Faker $faker) {
    return [
        'title' => $faker->word(),
        'category' => '文芸',
        'user_id' => factory(App\User::class)
    ];
});
