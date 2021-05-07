# [CHANGELOG](http://keepachangelog.com/)
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

### Removed
- Remove `ScriptSymlinker` class

## [0.3.1] - 2021-05-07

### Fixed
- Rename UPGRADE file

## [0.3.0] - 2021-05-07

### Added
- Add `Plugin` class and register package as a composer plugin
- Add `Symlinker` class

### Deprecated
- Deprecate `ScriptSymlinker` class

### Removed
- Remove support for Composer 1

## [0.2.5] - 2021-05-06

### Changed
- Require `composer-plugin-api` instead of `composer/composer`

## [0.2.4] - 2021-05-05

### Added
- Support for Composer 2

## [0.2.3] - 2021-05-05

### Fixed
- Remove old symlinks before recreating

## [0.2.2] - 2021-04-30

### Added
- Support NTFS junction

## [0.2.1] - 2017-11-27

### Changed
- Optimized symlink to package matching
- Update php-cs-fixer configuration

## [0.2.0] - 2016-05-27

### Added
- Add CHANGELOG.md file

### Changed
- Migration to PSR-4
- Improved CS
- Improved composer.json
- Leverage composer filesystem utility class
- Improve README

## 0.1.0 - 2016-05-24

[Unreleased]: https://github.com/ajgarlag/AjglComposerSymlinker/compare/0.3.1...master
[0.3.1]: https://github.com/ajgarlag/AjglComposerSymlinker/compare/0.3.0...0.3.1
[0.3.0]: https://github.com/ajgarlag/AjglComposerSymlinker/compare/0.2.5...0.3.0
[0.2.5]: https://github.com/ajgarlag/AjglComposerSymlinker/compare/0.2.4...0.2.5
[0.2.4]: https://github.com/ajgarlag/AjglComposerSymlinker/compare/0.2.3...0.2.4
[0.2.3]: https://github.com/ajgarlag/AjglComposerSymlinker/compare/0.2.2...0.2.3
[0.2.2]: https://github.com/ajgarlag/AjglComposerSymlinker/compare/0.2.1...0.2.2
[0.2.1]: https://github.com/ajgarlag/AjglComposerSymlinker/compare/0.2.0...0.2.1
[0.2.0]: https://github.com/ajgarlag/AjglComposerSymlinker/compare/0.1.0...0.2.0
