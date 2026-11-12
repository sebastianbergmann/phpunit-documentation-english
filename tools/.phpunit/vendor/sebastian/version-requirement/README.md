[![Latest Stable Version](https://poser.pugx.org/sebastian/version-requirement/v)](https://packagist.org/packages/sebastian/version-requirement)
[![CI Status](https://github.com/sebastianbergmann/version-requirement/workflows/CI/badge.svg)](https://github.com/sebastianbergmann/version-requirement/actions)
[![codecov](https://codecov.io/gh/sebastianbergmann/version-requirement/branch/main/graph/badge.svg)](https://codecov.io/gh/sebastianbergmann/version-requirement)

# sebastian/version-requirement

Library for checking whether a version satisfies a version requirement such as `^8.4` or `>= 8.4.0`, extracted from `phpunit/phpunit`.

## Scope

This library was extracted from PHPUnit, where it implements the version requirements of the `#[RequiresPhp]`, `#[RequiresPhpExtension]`, and `#[RequiresPhpunit]` attributes. These requirements are checked against the version of PHP (`PHP_VERSION`), the version of a PHP extension (`phpversion()`), and the version of PHPUnit.

The scope of this library is what PHPUnit needs for this. Anything that PHPUnit does not need is out of scope, even when Composer supports it. If you need to work with Composer's version constraints, use [composer/semver](https://github.com/composer/semver) instead.

## Installation

You can add this library as a local, per-project dependency to your project using [Composer](https://getcomposer.org/):

```
composer require sebastian/version-requirement
```

If you only need this library during development, for instance to run your project's test suite, then you should add it as a development-time dependency:

```
composer require --dev sebastian/version-requirement
```

## Usage

```php
<?php declare(strict_types=1);
use SebastianBergmann\VersionRequirement\Requirement;

$requirement = Requirement::from('^8.4 || ^9.0');

var_dump($requirement->isSatisfiedBy(PHP_VERSION));
```

`Requirement::from()` throws an `InvalidVersionRequirementException` when the given string is not a valid version requirement.

A version requirement is either a version constraint or a version comparison:

| Version requirement | Satisfied by                                                        |
|---------------------|---------------------------------------------------------------------|
| `8.5.1`             | `8.5.1`                                                             |
| `8.5.*`, `8.5.x`    | `>= 8.5.0` and `< 8.6.0`                                            |
| `8.*`, `8.x`        | `>= 8.0.0` and `< 9.0.0`                                            |
| `*`, `x`            | any version                                                         |
| `^8.5.1`            | `>= 8.5.1` and `< 9.0.0`                                            |
| `^0.5.1`            | `>= 0.5.1` and `< 0.6.0`                                            |
| `^0.0.5`            | `>= 0.0.5` and `< 0.0.6`                                            |
| `~8.5`              | `>= 8.5.0` and `< 9.0.0`                                            |
| `~8.5.1`            | `>= 8.5.1` and `< 8.6.0`                                            |
| `^7.4 \|\| ^8.0`    | versions that satisfy `^7.4` or `^8.0`                              |
| `>= 8.5.0`          | versions for which `version_compare($version, '8.5.0', '>=')` holds |

A version constraint may use a `v` prefix (`^v8.5`) and a pre-release suffix (`^8.5.0-RC1`). A version comparison has to use one of the operators `<`, `<=`, `>`, `>=`, `=`, `==`, `!=`, and `<>`.

## Differences to Composer

Version constraints use the syntax of [Composer's version constraints](https://getcomposer.org/doc/articles/versions.md), and the caret (`^`), tilde (`~`), and wildcard (`*`, `x`) operators as well as `||` work like they do in Composer. There are differences, however, and each of them has a reason.

### A version without operator has to consist of major, minor, and patch level

A version without operator, such as `8.5.1`, is matched exactly, just like in Composer. It has to consist of major, minor, and patch level, though: `8.5` and `8`, which Composer interprets as exactly `8.5.0` and `8.0.0`, are rejected. So is `8.5.0RC1` (pre-release suffix without `-`), which Composer interprets as exactly `8.5.0-RC1`. This also applies to alternatives combined using `||`: `8.4 || 8.5` is rejected.

Use a wildcard (`8.5.*`), the caret operator (`^8.5`), or the tilde operator (`~8.5.0`) to match a range of versions, and a version comparison (`>= 8.5.0RC1`) to compare against a pre-release.

**Why:** A version without operator that does not consist of major, minor, and patch level is ambiguous. `8.5` reads like "any release of 8.5", but Composer interprets it as exactly `8.5.0`, and older versions of PHPUnit interpreted `8.5.0RC1` as `>= 8.5.0RC1`. An ambiguous version requirement should be noticed instead of silently being interpreted in a way that was not intended.

### Version constraints ignore pre-release suffixes

A version constraint ignores pre-release suffixes, both its own and the one of the version that it is checked against: `8.5.0-dev` and `8.5.0RC1` are treated as `8.5.0`. For example, `8.5.0` is satisfied by `8.5.0-dev`, and `^8.5.0-RC2` is satisfied by `8.5.0RC1`. Composer orders pre-releases (`dev` < `alpha` < `beta` < `RC` < stable), so neither of these examples satisfies its version constraint in Composer.

A suffix still has to be valid, though: just like in Composer, it has to be a stability such as `-dev`, `-alpha1`, `-beta.2`, `-RC1`, or `-stable`.

**Why:** PHPUnit checks version requirements against `PHP_VERSION` and `phpversion()`. Development builds and release candidates of PHP report versions such as `8.5.0-dev` and `8.5.0RC1`, and the versions of PHP extensions do not follow a common format. PHPUnit treats a pre-release as the version it is a pre-release of.

### Version comparisons use `version_compare()`

A version comparison such as `>= 8.5.0` is evaluated using PHP's `version_compare()` function, whereas Composer normalizes versions before it compares them. This leads to different results:

* `<= 8.5` is not satisfied by `8.5.0` because `version_compare()` considers `8.5.0` to be greater than `8.5`. Composer treats `8.5` as `8.5.0`. `Requirement::isComplete()` returns `false` for such a version comparison, and PHPUnit warns about it.
* `< 8.5.0` is satisfied by `8.5.0RC1`. Composer excludes the pre-releases of `8.5.0` from `< 8.5.0`.

**Why:** This is how PHPUnit evaluates version comparisons, and existing tests rely on it. `version_compare()` is the function PHP provides for comparing "PHP-standardized" version numbers such as `PHP_VERSION`.

### Less syntax

The following syntax that Composer supports is not supported:

* Hyphen ranges: `1.0 - 2.0`
* Combining version requirements with AND: `>=8.1 <9.0`, `>=8.1, <9.0`
* Combining version comparisons with `||`, or a version comparison with a version constraint: `>=8.1 || <7.0`, `^8.1 || >=9.0`
* A `v` prefix in a version comparison: `>= v8.5.0` (the version that is checked may have one, though)
* Build metadata: `^1.2.3+build`
* Versions with more than three numbers: `1.2.3.4`
* Stability flags: `^8.1@dev`
* Branch names and aliases: `dev-main`, `2.x-dev`, `1.0 as 2.0`

**Why:** PHPUnit does not need it. A requirement on the version of PHP, a PHP extension, or PHPUnit can be expressed with a version constraint (with alternatives combined using `||`, if needed) or with a single version comparison.

### Stricter validation

Composer accepts some malformed version constraints, such as `^1.`, `~1.`, and `1.2.`, because they have historically been common. This library rejects them.

**Why:** PHPUnit reports an invalid version requirement as an error for the test that uses it. A mistyped version requirement should be noticed instead of silently being interpreted.
