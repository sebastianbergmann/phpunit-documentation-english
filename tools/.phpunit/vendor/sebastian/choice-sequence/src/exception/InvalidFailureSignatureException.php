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
 * Thrown when a failure signature cannot be recreated from a kind and a text.
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for sebastian/choice-sequence
 */
final class InvalidFailureSignatureException extends RuntimeException implements Exception
{
    public static function unknownKind(string $kind): self
    {
        return new self(
            sprintf(
                '"%s" is not a kind of failure, expected "throwable", "timeout", or "description"',
                $kind,
            ),
        );
    }

    public static function emptyText(): self
    {
        return new self('The text of a failure signature must not be empty');
    }

    private function __construct(string $message)
    {
        parent::__construct($message);
    }
}
