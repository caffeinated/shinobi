<?php

use Illuminate\Support\Str;
use Caffeinated\Shinobi\Models\Permission;

$factory->define(Permission::class, function(Faker\Generator $faker) {
    $name = $faker->unique()->sentence(2);

    return [
        'name'        => $name,
        'slug'        => Str::slug($name, '.'),
        'description' => $faker->sentence,
    ];
});