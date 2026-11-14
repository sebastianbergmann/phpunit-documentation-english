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
use RuntimeException;

/**
 * Thrown when a choice is to be drawn while the maximum number of choices
 * has already been drawn.
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for sebastian/choice-sequence
 */
final class TooManyChoicesException extends RuntimeException implements Exception
{
    /**
     * @var positive-int
     */
    private readonly int $maximumNumberOfChoices;

    /**
     * @param positive-int $maximumNumberOfChoices
     */
    public function __construct(int $maximumNumberOfChoices)
    {
        parent::__construct(
            sprintf(
                'Cannot draw more than %d choices',
                $maximumNumberOfChoices,
            ),
        );

        $this->maximumNumberOfChoices = $maximumNumberOfChoices;
    }

    /**
     * @return positive-int
     */
    public function maximumNumberOfChoices(): int
    {
        return $this->maximumNumberOfChoices;
    }
}
