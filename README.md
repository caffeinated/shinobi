Caffeinated Shinobi
===================
[![Laravel 5.5](https://img.shields.io/badge/Laravel-5.3-orange.svg?style=flat-square)](http://laravel.com)
[![Source](http://img.shields.io/badge/source-caffeinated/shinobi-blue.svg?style=flat-square)](https://github.com/caffeinated/shinobi)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)

Shinobi brings a simple and light-weight role-based permissions system to Laravel's built in Auth system. Shinobi brings support for the following ACL structure:

- Every user can have zero or more roles.
- Every role can have zero or more permissions.

Permissions are then inherited to the user through the user's assigned roles.

The package follows the FIG standards PSR-1, PSR-2, and PSR-4 to ensure a high level of interoperability between shared PHP code. At the moment the package is not unit tested, but is planned to be covered later down the road.

Documentation
-------------
You will find user friendly documentation in the wiki here: [Caffeinated Shinobi Wiki](https://github.com/caffeinated/shinobi/wiki)

Quick Installation
------------------
Begin by installing the package through Composer. The best way to do this is through your terminal via Composer itself:

```
composer require caffeinated/shinobi
```

Once this operation is complete, simply add the service provider to your project's `config/app.php` file and run the provided migrations against your database.

### Service Provider
```php
Caffeinated\Shinobi\ShinobiServiceProvider::class
```

### Migrations
You'll need to run the provided migrations against your database. Publish the migration files using the `vendor:publish` Artisan command and run `migrate`:

```
php artisan vendor:publish
php artisan migrate
```

Awesome Shinobi
---------------
See what the awesome community behind Shinobi has built. Created something you'd like added? Send a pull-request or open an issue!

### Open Source

- [The Watchtower](https://github.com/SmarchSoftware/watchtower) - A front-end (GUI) package.
