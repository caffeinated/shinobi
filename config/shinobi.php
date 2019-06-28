<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Experimental Cache
    |--------------------------------------------------------------------------
    |
    | Shinobi ships with an experimental caching layer in an attempt to lessen
    | the load on resources when checking and validating permissions. By
    | default this is disabled, please enable to provide feedback.
    */

    'cache' => [

        /**
         * You may enable or disable the built in caching system. This is useful
         * when debugging your application. If your application already has its
         * own caching layer, we suggest disabling the cache here as well.
         */

        'enabled' => false,

        /**
         * Define the length of time permissions should be cached for before being
         * refreshed. Accepted values are either in seconds or as a DateInterval
         * object. By default we cache for 86400 seconds (aka, 24 hours).
         */

        'length' => 60 * 60 * 24,

        /**
         * When using a cache driver that supports tags, we'll tag the shinobi
         * cache with this tag. This is useful for busting only the cache
         * responsible for storing permissions and not anything else.
         */

        'tag' => 'shinobi',

    ],

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
