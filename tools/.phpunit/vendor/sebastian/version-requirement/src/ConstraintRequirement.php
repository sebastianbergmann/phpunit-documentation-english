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
 * @immutable
 *
 * @no-named-arguments
 */
final readonly class ConstraintRequirement extends Requirement
{
    /**
     * @var non-empty-string
     */
    private string $constraint;

    /**
     * @var non-empty-list<VersionRange>
     */
    private array $ranges;

    /**
     * @param non-empty-string $constraint
     *
     * @throws InvalidVersionRequirementException
     */
    public function __construct(string $constraint)
    {
        $this->constraint = $constraint;
        $this->ranges     = (new VersionConstraintParser)->parse($constraint);
    }

    /**
     * Pre-release suffixes and build metadata are ignored: "8.5.0-dev" satisfies
     * "^8.5", for example. Returns false when the given string does not start
     * with a version number.
     */
    public function isSatisfiedBy(string $version): bool
    {
        $version = Version::from($version);

        if ($version === null) {
            return false;
        }

        foreach ($this->ranges as $range) {
            if ($range->contains($version)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return non-empty-string
     */
    public function asString(): string
    {
        return $this->constraint;
    }
}
