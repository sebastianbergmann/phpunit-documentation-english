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

use function sprintf;
use RuntimeException;

/**
 * @no-named-arguments
 */
final class InvalidVersionRequirementException extends RuntimeException implements Exception
{
    public function __construct(string $versionRequirement)
    {
        parent::__construct(
            sprintf(
                '"%s" is not a valid version requirement: expected a version constraint (such as "^8.1", "~8.1.0", or "8.1.*") or a version comparison (such as ">= 8.1.0")',
                $versionRequirement,
            ),
        );
    }
}
