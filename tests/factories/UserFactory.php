<?php

use Caffeinated\Shinobi\Tests\User;

$factory->define(User::class, function(Faker\Generator $faker) {
    return [
        'name'        => $faker->name,
        'email'       => $faker->unique()->safeEmail,
        'password'    => bcrypt($faker->password),
    ];
});