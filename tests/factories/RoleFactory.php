<?php

use Caffeinated\Shinobi\Models\Role;

$factory->define(Role::class, function(Faker\Generator $faker) {
    $name = $faker->unique()->jobTitle;

    return [
        'name'        => $name,
        'slug'        => str_slug($name),
        'description' => $faker->sentence,
        'special'     => null,
    ];
});