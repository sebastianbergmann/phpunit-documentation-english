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
use function assert;
use function preg_match;

/**
 * @immutable
 *
 * @no-named-arguments
 *
 * @internal This class is not covered by the backward compatibility promise for sebastian/version-requirement
 */
final readonly class Version
{
    /**
     * @var non-negative-int
     */
    private int $major;

    /**
     * @var non-negative-int
     */
    private int $minor;

    /**
     * @var non-negative-int
     */
    private int $patch;

    /**
     * Returns null when the given string does not start with a version number.
     *
     * Missing minor and patch levels are treated as 0. Anything that follows the
     * version number, such as a pre-release suffix ("8.5.0-dev" or "8.5.0RC1") or
     * build metadata ("8.5.0+build"), is ignored.
     */
    public static function from(string $version): ?self
    {
        if (preg_match('/^v?(\d+)(?:\.(\d+))?(?:\.(\d+))?/i', $version, $matches, PREG_UNMATCHED_AS_NULL) !== 1) {
            return null;
        }

        $major = (int) $matches[1];
        $minor = (int) $matches[2];
        $patch = (int) $matches[3];

        // The regular expression only matches digits
        assert($major >= 0 && $minor >= 0 && $patch >= 0);

        return new self($major, $minor, $patch);
    }

    /**
     * @param non-negative-int $major
     * @param non-negative-int $minor
     * @param non-negative-int $patch
     */
    public function __construct(int $major, int $minor, int $patch)
    {
        $this->major = $major;
        $this->minor = $minor;
        $this->patch = $patch;
    }

    public function isLessThan(self $other): bool
    {
        return [$this->major, $this->minor, $this->patch] < [$other->major, $other->minor, $other->patch];
    }
}
