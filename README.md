Caffeinated Shinobi
===================
[![Laravel](https://img.shields.io/badge/Laravel-5.0-orange.svg?style=flat-square)](http://laravel.com)
[![Source](http://img.shields.io/badge/source-caffeinated/shinobi-blue.svg?style=flat-square)](https://github.com/caffeinated/shinobi)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)

Shinobi brings a simple role-based permissions system to Laravel's auth system.

Installation
------------
Simply run the provided migrations against your database. At the moment there is no publish method, so you'll need to manually copy the migrations to the correct location within your app. This is normally at `app/database/migrations`.

Usage
-----

The Caffeinated Shinobi package comes bundled with a ShinobiTrait file to be used within your User's Model file. This trait file provides all the necessary functions to tie your users in with roles and permissions.

**Example:**

```php
<?php

use Illuminate\Database\Eloquent\Model;
use Caffeinated\Shinobi\Traits\ShinobiTrait;

class User extends Model
{
	use ShinobiTrait;

	...
}
```

### Trait Methods

#### is($roleSlug)
Checks if the user is under the given role.

```php
Auth::user()->is('administrator');
```

You may also use magic methods:

```php
Auth::user()->isAdministrator();
```

#### assignRole($roleId)
Assign the given role to the user.

```php
Auth::user()->assignRole(1);
```

#### revokeRole($roleId)
Revokes the given role from the user.

```php
Auth::user()->revokeRole(1);
```

#### revokeAllRoles()
Revokes all roles from the user.

```php
Auth::user()->revokeAllRoles();
```

#### syncRoles([$roleIds])
Syncs the given roles with the user. This will revoke any roles not supplied.

```php
Auth::user()->syncRoles([1, 2, 3]);
```
