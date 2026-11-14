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
 * Tells whether two failures are the same: when they are of the same kind
 * and, unless they exceeded the time limit, have the same text. Failures
 * that ended with a throwable are the same when the throwables have the
 * same class and were raised at the same location, failures that exceeded
 * the time limit are always the same, and failures that are described
 * otherwise are the same when they are described the same way. Failures of
 * different kinds are never the same, even when their texts are.
 *
 * Shrinking keeps a simpler choice sequence only when replaying it fails in
 * the same way as the counterexample.
 *
 * @immutable
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for sebastian/choice-sequence
 */
final readonly class FailureSignature
{
    private const int THROWABLE   = 0;
    private const int TIMEOUT     = 1;
    private const int DESCRIPTION = 2;

    /**
     * @var self::DESCRIPTION|self::THROWABLE|self::TIMEOUT
     */
    private int $kind;

    /**
     * @var non-empty-string
     */
    private string $signature;

    /**
     * @param class-string     $className the class of the throwable that ended the example
     * @param non-empty-string $location  where the throwable was raised, for instance "/path/to/FooTest.php:12"
     */
    public static function forThrowable(string $className, string $location): self
    {
        return new self(
            self::THROWABLE,
            sprintf(
                '%s at %s',
                $className,
                $location,
            ),
        );
    }

    public static function forTimeout(): self
    {
        return new self(self::TIMEOUT, 'timeout');
    }

    /**
     * @param non-empty-string $description describes a failure that did not end with a throwable
     */
    public static function forDescription(string $description): self
    {
        return new self(self::DESCRIPTION, $description);
    }

    /**
     * Recreates a failure signature from its kind and its text, for instance
     * after they were stored with a counterexample: the signature is the same
     * as the one whose kind() and text() they are.
     *
     * @throws InvalidFailureSignatureException
     */
    public static function fromKindAndText(string $kind, string $text): self
    {
        if ($text === '') {
            throw InvalidFailureSignatureException::emptyText();
        }

        return match ($kind) {
            'throwable'   => new self(self::THROWABLE, $text),
            'timeout'     => self::forTimeout(),
            'description' => new self(self::DESCRIPTION, $text),
            default       => throw InvalidFailureSignatureException::unknownKind($kind),
        };
    }

    /**
     * @param self::DESCRIPTION|self::THROWABLE|self::TIMEOUT $kind
     * @param non-empty-string                                $signature
     */
    private function __construct(int $kind, string $signature)
    {
        $this->kind      = $kind;
        $this->signature = $signature;
    }

    /**
     * Whether the example was aborted because it exceeded its time limit.
     */
    public function isTimeout(): bool
    {
        return $this->kind === self::TIMEOUT;
    }

    /**
     * Whether the failure ended with a throwable, exceeded the time limit, or
     * is described otherwise. Together with the text, the kind tells failures
     * apart and can be stored to recreate the signature (see fromKindAndText()).
     *
     * @return 'description'|'throwable'|'timeout'
     */
    public function kind(): string
    {
        return match ($this->kind) {
            self::THROWABLE   => 'throwable',
            self::TIMEOUT     => 'timeout',
            self::DESCRIPTION => 'description',
        };
    }

    /**
     * The text that, together with the kind, tells failures apart, for
     * instance "RuntimeException at /path/to/FooTest.php:12" for a throwable,
     * the description of a failure that is described otherwise, and "timeout"
     * for a failure that exceeded the time limit. Unlike description(), it
     * is not meant to be shown to people.
     *
     * @return non-empty-string
     */
    public function text(): string
    {
        return $this->signature;
    }

    public function isSameAs(self $other): bool
    {
        return $this->kind === $other->kind &&
               $this->signature === $other->signature;
    }

    /**
     * Describes the failure, for instance "RuntimeException at
     * /path/to/FooTest.php:12" or "exceeding the time limit".
     *
     * @return non-empty-string
     */
    public function description(): string
    {
        if ($this->kind === self::TIMEOUT) {
            return 'exceeding the time limit';
        }

        return $this->signature;
    }
}
