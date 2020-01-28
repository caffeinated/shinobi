# Caffeinated Shinobi
[![Source](https://img.shields.io/badge/source-caffeinated/shinobi-blue.svg?style=flat-square)](https://github.com/caffeinated/shinobi)
[![Latest Stable Version](https://poser.pugx.org/caffeinated/shinobi/v/stable?format=flat-square)](https://packagist.org/packages/caffeinated/shinobi)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)
[![Total Downloads](https://img.shields.io/packagist/dt/caffeinated/shinobi.svg?style=flat-square)](https://packagist.org/packages/caffeinated/shinobi)
[![Travis (.org)](https://img.shields.io/travis/caffeinated/shinobi.svg?style=flat-square)](https://travis-ci.org/caffeinated/shinobi)

A simple and light-weight role-based permissions system for Laravel's Authorization Gate system. Originally developed for [FusionCMS](https://github.com/fusioncms/fusioncms), an open source content management system.

- Every user can have zero or more permissions.
- Every user can have zero or more roles.
- Every role can have zero or more permissions.
- Every role can have one of two special flags, `all-access` and `no-access`

## Documentation
You will find user friendly and updated documentation on the [Caffeinated website](https://caffeinatedpackages.com/guide/packages/shinobi.html).

## Installation
Simply install the package through Composer. From here the package will automatically register its service provider and `Shinobi` facade.

```
composer require caffeinated/shinobi
```

### Config
To publish the config file, run the following:

```
php artisan vendor:publish --provider="Caffeinated\Shinobi\ShinobiServiceProvider" --tag="config"
```

## Changelog
You will find a complete changelog history within the [CHANGELOG](CHANGELOG.md) file.

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Testing
Run tests with PHPUnit:

```bash
vendor/bin/phpunit
```

## Security
If you discover any security related issues, please email shea.lewis89@gmail.com directly instead of using the issue tracker.

## Credits
- [Shea Lewis](https://github.com/kaidesu)
- [All Contributors](../../contributors)

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
