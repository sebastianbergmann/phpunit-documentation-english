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
 * Thrown when no element that is added to a collection is drawn in several
 * attempts, because each of them duplicates an earlier element, while the
 * collection has fewer elements than its minimum size requires.
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for sebastian/choice-sequence
 */
final class TooManyDuplicateElementsException extends RuntimeException implements Exception
{
    /**
     * @var positive-int
     */
    private readonly int $numberOfAttempts;

    /**
     * @param positive-int $numberOfAttempts
     */
    public function __construct(int $numberOfAttempts)
    {
        parent::__construct(
            sprintf(
                'No new element for the collection was drawn in %d attempts',
                $numberOfAttempts,
            ),
        );

        $this->numberOfAttempts = $numberOfAttempts;
    }

    /**
     * @return positive-int
     */
    public function numberOfAttempts(): int
    {
        return $this->numberOfAttempts;
    }
}
