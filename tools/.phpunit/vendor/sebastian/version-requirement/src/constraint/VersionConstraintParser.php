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

use const PREG_UNMATCHED_AS_NULL;
use function array_map;
use function assert;
use function explode;
use function in_array;
use function preg_match;
use function str_contains;
use function str_replace;
use function trim;

/**
 * Parses version constraints such as "^8.1", "~8.1.0", "8.1.*", or "^7.4 || ^8.0"
 * into the ranges of versions that satisfy them.
 *
 * @immutable
 *
 * @no-named-arguments
 *
 * @internal This class is not covered by the backward compatibility promise for sebastian/version-requirement
 */
final readonly class VersionConstraintParser
{
    private const string SYNTAX     = '/^[\^~*]?v?[\d.*x]+(?:-.*)?$/i';
    private const string CONSTRAINT = '/^(?P<operator>[\^~])?v?(?P<major>\d+|[*x])(?:\.(?P<minor>\d+|[*x])(?:\.(?P<patch>\d+|[*x]))?)?(?P<suffix>-(?:dev|(?:stable|beta|b|rc|alpha|a|patch|pl|p)(?:[.-]?\d+)*(?:[.-]?dev)?))?$/i';

    /**
     * Returns true when the given string uses the syntax of a version constraint,
     * even when it is not a valid one ("^8.*", for example), and false when it
     * does not (">= 8.1", for example).
     */
    public function looksLikeVersionConstraint(string $constraint): bool
    {
        foreach ($this->alternatives($constraint) as $alternative) {
            if (preg_match(self::SYNTAX, $alternative) !== 1) {
                return false;
            }
        }

        return true;
    }

    /**
     * @throws InvalidVersionRequirementException
     *
     * @return non-empty-list<VersionRange>
     */
    public function parse(string $constraint): array
    {
        $ranges = [];

        foreach ($this->alternatives($constraint) as $alternative) {
            $ranges[] = $this->parseAlternative($alternative) ?? throw new InvalidVersionRequirementException($constraint);
        }

        return $ranges;
    }

    /**
     * @return non-empty-list<string>
     */
    private function alternatives(string $constraint): array
    {
        if (!str_contains($constraint, '|')) {
            return [$constraint];
        }

        return array_map(trim(...), explode('|', str_replace('||', '|', $constraint)));
    }

    private function parseAlternative(string $constraint): ?VersionRange
    {
        if (preg_match(self::CONSTRAINT, $constraint, $matches, PREG_UNMATCHED_AS_NULL) !== 1) {
            return null;
        }

        $operator = $matches['operator'];
        $major    = $this->normalizeWildcard($matches['major']);
        $minor    = $this->normalizeWildcard($matches['minor']);
        $patch    = $this->normalizeWildcard($matches['patch']);

        $hasWildcard = in_array('*', [$major, $minor, $patch], true);

        // A wildcard cannot be combined with an operator ("^8.*") or a suffix ("8.*-dev")
        if (($operator !== null || $matches['suffix'] !== null) && $hasWildcard) {
            return null;
        }

        // A version without operator or wildcard has to consist of major, minor, and patch level ("8.5.0", not "8.5")
        if ($operator === null && !$hasWildcard && $patch === null) {
            return null;
        }

        // "8.5.*" means the same as "8.5", "8.*" means the same as "8", and "*" matches any version
        if ($patch === '*') {
            $patch = null;
        }

        if ($minor === '*' && $patch === null) {
            $minor = null;
        }

        if ($major === '*' && $minor === null) {
            return new VersionRange(new Version(0, 0, 0), null);
        }

        // A wildcard that is followed by a version number, such as "8.*.1"
        if ($major === '*' || $minor === '*') {
            return null;
        }

        return $this->range(
            $operator,
            $this->toInteger($major),
            $minor !== null ? $this->toInteger($minor) : null,
            $patch !== null ? $this->toInteger($patch) : null,
        );
    }

    /**
     * @param non-negative-int  $major
     * @param ?non-negative-int $minor
     * @param ?non-negative-int $patch
     */
    private function range(?string $operator, int $major, ?int $minor, ?int $patch): VersionRange
    {
        // "8.*", "^8", and "~8" are satisfied by 8.x.y
        if ($minor === null) {
            return new VersionRange(new Version($major, 0, 0), new Version($major + 1, 0, 0));
        }

        $lowerBound = new Version($major, $minor, $patch ?? 0);

        // "^8.5.1" is satisfied by 8.x.y from 8.5.1, "^0.5.1" by 0.5.y from 0.5.1, and "^0.0.1" by 0.0.1 only
        if ($operator === '^') {
            return new VersionRange(
                $lowerBound,
                match (true) {
                    $major > 0                    => new Version($major + 1, 0, 0),
                    $minor > 0 || $patch === null => new Version(0, $minor + 1, 0),
                    default                       => new Version(0, 0, $patch + 1),
                },
            );
        }

        // "~8.5" is satisfied by 8.x.y from 8.5.0
        if ($operator === '~' && $patch === null) {
            return new VersionRange($lowerBound, new Version($major + 1, 0, 0));
        }

        // "~8.5.1" is satisfied by 8.5.y from 8.5.1, "8.5.*" by 8.5.y
        if ($operator === '~' || $patch === null) {
            return new VersionRange($lowerBound, new Version($major, $minor + 1, 0));
        }

        return new VersionRange($lowerBound, new Version($major, $minor, $patch + 1));
    }

    /**
     * "x" and "X" are wildcards just like "*": "8.5.x" means the same as "8.5.*", for example.
     *
     * @return ($level is null ? null : string)
     */
    private function normalizeWildcard(?string $level): ?string
    {
        return $level === 'x' || $level === 'X' ? '*' : $level;
    }

    /**
     * Must only be called for a level that consists of digits ("8", not "*").
     *
     * @return non-negative-int
     */
    private function toInteger(string $level): int
    {
        $integer = (int) $level;

        assert($integer >= 0);

        return $integer;
    }
}
