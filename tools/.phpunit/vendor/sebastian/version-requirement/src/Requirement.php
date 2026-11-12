<?php declare(strict_types=1);
/*
 * This file is part of sebastian/version-requirement.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\VersionRequirement;

use function preg_match;

/**
 * @immutable
 *
 * @no-named-arguments
 */
abstract readonly class Requirement
{
    private const string VERSION_COMPARISON = "/^(?P<operator>!=|<|<=|<>|=|==|>|>=)\s*(?P<version>[\d\.-]+(dev|(RC|rc|alpha|beta)[\d\.]+)?)[ \t]*\r?$/";

    /**
     * @param non-empty-string $versionRequirement
     *
     * @throws InvalidVersionOperatorException
     * @throws InvalidVersionRequirementException
     */
    public static function from(string $versionRequirement): self
    {
        if ((new VersionConstraintParser)->looksLikeVersionConstraint($versionRequirement)) {
            return new ConstraintRequirement($versionRequirement);
        }

        if (preg_match(self::VERSION_COMPARISON, $versionRequirement, $matches) > 0) {
            return new ComparisonRequirement(
                $matches['version'],
                new VersionComparisonOperator($matches['operator']),
            );
        }

        throw new InvalidVersionRequirementException($versionRequirement);
    }

    abstract public function isSatisfiedBy(string $version): bool;

    abstract public function asString(): string;

    /**
     * Returns false when this requirement is a version comparison and its version
     * does not consist of major, minor, and patch level ("8.5" instead of "8.5.0",
     * for example).
     *
     * Comparing an incomplete version may not behave as intended:
     * version_compare('8.5.0', '8.5', '<=') is false, for example.
     *
     * Version constraints such as "^8.5" or "8.5.*" are always considered
     * complete: they are not evaluated using version_compare(), and their
     * semantics are well-defined even when major, minor, and patch level
     * are not specified ("^8.5" means ">= 8.5.0, < 9.0.0", for example).
     */
    public function isComplete(): bool
    {
        return true;
    }
}
