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
final class InvalidVersionOperatorException extends RuntimeException implements Exception
{
    public function __construct(string $operator)
    {
        parent::__construct(
            sprintf(
                '"%s" is not a valid version_compare() operator: expected one of "<", "lt", "<=", "le", ">", "gt", ">=", "ge", "==", "=", "eq", "!=", "<>", "ne"',
                $operator,
            ),
        );
    }
}
