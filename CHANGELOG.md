# Changelog
All notable changes to this package will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this package adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [5.1.0] - 2020-04-02
### Added
- Laravel 7 support

## [5.0.0] - 2019-09-05
### Removed
- References to deprecated Laravel helper methods
- Laravel 5 support

### Changed
- PHP 7.2 is now the minimum supported version

## [4.3.0] - 2019-08-30
### Added
- `PermissionNotFoundException` will now be thrown if a permission is not found during check. You may catch and report against this from within your application :v:

### Fixed
- No longer using the `firstOrFail` method when checking for permissions, which means no more discrepancies when caching is enabled
- `hasPermissionThroughRole` now checks the role directly for permissions

## [4.2.0] - 2019-08-06
### Added
- Laravel 6.x support

## [4.1.0] - 2019-07-03
### Added
- New blade directives; `@anyrole`, `@elseanyrole`, `@endanyrole`, `@allroles`, `@elseallroles`, and `@endallroles`
- `hasPermissionFlags` and `hasPermissionThroughFlag` added to Role contract
- Experimental caching layer; enable within your `shinobi` config file
- `UserHasAllRoles` and `UserHasAnyRole` middlewares
- Configuration options to customize table names
- Complete test coverage :feelsgood:

### Changed
- `@role` Blade directive now uses Laravel's `Blade::if()` implementation, which supports `@role`, `@elserole`, and `@endrole`

## [4.0.1] - 2019-06-05
### Fixed
- Incorrect migration publish path
