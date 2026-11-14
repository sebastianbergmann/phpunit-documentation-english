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

use function array_key_exists;
use function array_splice;
use function assert;
use function count;

/**
 * The values that choices refer to: the values that the steps of a state
 * machine produce, and that later steps take, for instance.
 *
 * A value that is added to the pool is marked with a choice that is always 0
 * in a span of the kind SpanKind::Referable. A value is taken from the pool
 * with a choice in a span of the kind SpanKind::Reference, or
 * SpanKind::ConsumingReference when the value is removed from the pool: its
 * position among the values of the pool, counted from the one that was added
 * last, which is the simplest one. The spans are labelled with the label of
 * the pool.
 *
 * These spans tell the shrinker which value a reference refers to. When it
 * deletes the choices that added a value to the pool, or that removed one,
 * it changes the references after them so that they refer to the values that
 * they referred to before (see Shrinker).
 *
 * @template T
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for sebastian/choice-sequence
 */
final class Pool
{
    /**
     * @var non-empty-string
     */
    private readonly string $label;

    /**
     * @var list<T>
     */
    private array $values = [];

    /**
     * @param non-empty-string $label the label of the spans of the values of the pool and of the references to them (see Label)
     */
    public function __construct(string $label)
    {
        $this->label = $label;
    }

    /**
     * @return non-empty-string
     */
    public function label(): string
    {
        return $this->label;
    }

    public function isEmpty(): bool
    {
        return $this->values === [];
    }

    /**
     * @return non-negative-int
     */
    public function count(): int
    {
        return count($this->values);
    }

    /**
     * Adds a value to the pool, and draws the choice that marks it.
     *
     * @param T $value
     *
     * @throws TooManyChoicesException
     */
    public function add(ChoiceSource $source, mixed $value): void
    {
        $source->startSpan($this->label, SpanKind::Referable);

        try {
            $source->draw(0, new ExactDistribution(0));
        } finally {
            $source->endSpan();
        }

        $this->values[] = $value;
    }

    /**
     * Draws a reference to one of the values of the pool, and returns the
     * value. The value stays in the pool.
     *
     * @throws NoValueToReferToException
     * @throws TooManyChoicesException
     *
     * @return T
     */
    public function refer(ChoiceSource $source): mixed
    {
        $index = $this->draw($source, SpanKind::Reference);

        assert(array_key_exists($index, $this->values));

        return $this->values[$index];
    }

    /**
     * Draws a reference to one of the values of the pool, removes the value
     * from the pool, and returns it.
     *
     * @throws NoValueToReferToException
     * @throws TooManyChoicesException
     *
     * @return T
     */
    public function consume(ChoiceSource $source): mixed
    {
        $index = $this->draw($source, SpanKind::ConsumingReference);

        assert(array_key_exists($index, $this->values));

        $value = $this->values[$index];

        array_splice($this->values, $index, 1);

        return $value;
    }

    /**
     * @throws NoValueToReferToException
     * @throws TooManyChoicesException
     *
     * @return int the index of the value that the reference that was drawn refers to
     */
    private function draw(ChoiceSource $source, SpanKind $kind): int
    {
        if ($this->values === []) {
            throw new NoValueToReferToException;
        }

        $last = count($this->values) - 1;

        $source->startSpan($this->label, $kind);

        try {
            $position = $source->draw($last, new UniformDistribution);
        } finally {
            $source->endSpan();
        }

        return $last - $position;
    }
}
