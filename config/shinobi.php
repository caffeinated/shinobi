<?php

return [

    'models' => [

        /*
        |--------------------------------------------------------------------------
        | Model References
        |--------------------------------------------------------------------------
        |
        | Shinobi needs to know which Eloquent Models should be referenced during
        | actions such as registering and checking for permissions, assigning
        | permissions to roles and users, and assigning roles to users.
        */

        'role' => Caffeinated\Shinobi\Models\Role::class,
        'permission' => Caffeinated\Shinobi\Models\Permission::class,

    ],

    'tables' => [

        /*
        |--------------------------------------------------------------------------
        | Table References
        |--------------------------------------------------------------------------
        |
        | When customizing the models used by Shinobi, you may also wish to
        | customize the table names as well. You will want to publish the
        | bundled migrations and update the references here for use.
        */

        'roles' => 'roles',
        'permissions' => 'permissions',
        'role_user' => 'role_user',
        'permission_role' => 'permission_role',
        'permission_user' => 'permission_user',

    ],

    /*
    |--------------------------------------------------------------------------
    | Use Migrations
    |--------------------------------------------------------------------------
    |
    | Shinobi comes packaged with everything out of the box for you, including
    | migrations. If instead you wish to customize or extend Shinobi beyond
    | its offering, you may disable the provided migrations for your own.
    */

    'migrate' => true,

];
