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

/**
 * Versions from a lower bound (inclusive) up to an upper bound (exclusive).
 * A range without an upper bound contains all versions from its lower bound.
 *
 * @immutable
 *
 * @no-named-arguments
 *
 * @internal This class is not covered by the backward compatibility promise for sebastian/version-requirement
 */
final readonly class VersionRange
{
    private Version $lowerBound;
    private ?Version $upperBound;

    public function __construct(Version $lowerBound, ?Version $upperBound)
    {
        $this->lowerBound = $lowerBound;
        $this->upperBound = $upperBound;
    }

    public function contains(Version $version): bool
    {
        if ($version->isLessThan($this->lowerBound)) {
            return false;
        }

        return $this->upperBound === null || $version->isLessThan($this->upperBound);
    }
}
