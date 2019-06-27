<?php

use Caffeinated\Shinobi\Models\Permission;

$factory->define(Permission::class, function(Faker\Generator $faker) {
    $name = $faker->unique()->sentence(2);

    return [
        'name'        => $name,
        'slug'        => str_slug($name, '.'),
        'description' => $faker->sentence,
    ];
});