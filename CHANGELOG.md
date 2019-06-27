# Changelog
All notable changes to this package will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this package adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [4.1.0] - Unreleased
### Added
- `hasPermissionFlags` and `hasPermissionThroughFlag` added to Role contract
- Configuration options to customize table names
- Close to 40 unit tests :feelsgood:

### Changed
- `@role` Blade directive now uses Laravel's `Blade::if()` implementation, which supports `@role`, `@elserole`, and `@endrole`

## [4.0.1] - 2019-06-05
### Fixed
- Incorrect migration publish path
