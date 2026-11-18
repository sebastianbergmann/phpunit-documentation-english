<?php declare(strict_types=1);
/*
 * This file is part of sebastian/choice-sequence.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\ChoiceSequence;

use function sprintf;

/**
 * Thrown when an argument is not valid in a way that its type cannot tell.
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for sebastian/choice-sequence
 */
final class InvalidArgumentException extends \InvalidArgumentException implements Exception
{
    /**
     * @param non-negative-int $minimumSize
     * @param non-negative-int $maximumSize
     */
    public static function maximumSizeSmallerThanMinimumSize(int $minimumSize, int $maximumSize): self
    {
        return new self(
            sprintf(
                'The maximum size %d of a collection is smaller than its minimum size %d',
                $maximumSize,
                $minimumSize,
            ),
        );
    }

    /**
     * @param non-negative-int $minimum
     * @param non-negative-int $maximum
     */
    public static function minimumOfRangeLargerThanMaximum(int $minimum, int $maximum): self
    {
        return new self(
            sprintf(
                'The minimum %d of a range of choices is larger than its maximum %d',
                $minimum,
                $maximum,
            ),
        );
    }

    public static function stringNotValidUtf8(): self
    {
        return new self('The string is not valid UTF-8');
    }

    private function __construct(string $message)
    {
        parent::__construct($message);
    }
}
