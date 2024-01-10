# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## v1.1.0 (2024-01-10)
### Added
-   Add support for Tailwind CSS v3.4

### Fixed
-   Fix display class not removed when it precedes line-clamp class

## v1.0.0 (2023-10-18)
### Added
- Add factory method to provide an optional PSR-16 cache
- Add missing class col-span-full
- Allow length and percentage labels for arbitrary sizes
- Add configuration documentation

### Changed
- Remove illuminate/support dependency - implements #3
- Refactor validators
- Replace ArbitraryUrlValidator with ArbitraryImageValidator
- Split arbitrary and non-arbitrary validators into distinct validators
- Ssplit touch class group into touch, touch-x, touch-y and touch-pz
- Tests against PHP 8.3

### Fixed
- fix ambiguous arbitrary values

## v0.0.1 (2023-06-16)
### Added
- Initial Release
