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

use const PHP_INT_MAX;
use function sprintf;
use RuntimeException;

/**
 * Thrown when an encoded choice sequence cannot be decoded.
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for sebastian/choice-sequence
 */
final class InvalidChoiceSequenceException extends RuntimeException implements Exception
{
    /**
     * @var positive-int
     */
    private readonly int $invalidLine;

    /**
     * @param non-empty-string $header
     */
    public static function invalidHeader(string $header): self
    {
        return new self(
            sprintf(
                'Line 1 of the encoded choice sequence is not "%s"',
                $header,
            ),
            1,
        );
    }

    /**
     * @param positive-int $line
     */
    public static function invalidChoice(int $line, string $content): self
    {
        return new self(
            sprintf(
                'Line %d of the encoded choice sequence is not a decimal integer between 0 and %d without sign and leading zeros: "%s"',
                $line,
                PHP_INT_MAX,
                $content,
            ),
            $line,
        );
    }

    /**
     * @param positive-int $line
     */
    public static function missingLineBreak(int $line): self
    {
        return new self(
            sprintf(
                'Line %d of the encoded choice sequence does not end with a line break',
                $line,
            ),
            $line,
        );
    }

    /**
     * @param positive-int $line
     */
    public static function inconsistentLineBreak(int $line): self
    {
        return new self(
            sprintf(
                'Line %d of the encoded choice sequence does not end with the same line break as line 1',
                $line,
            ),
            $line,
        );
    }

    /**
     * @param positive-int $line
     */
    private function __construct(string $message, int $line)
    {
        parent::__construct($message);

        $this->invalidLine = $line;
    }

    /**
     * The number of the line that is not valid, counting from 1.
     *
     * @return positive-int
     */
    public function invalidLine(): int
    {
        return $this->invalidLine;
    }
}
