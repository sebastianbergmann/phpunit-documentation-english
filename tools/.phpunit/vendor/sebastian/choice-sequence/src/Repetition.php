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

use function min;
use Closure;

/**
 * Draws the elements of a collection: first the elements that its minimum
 * size requires, then, as long as its maximum size permits, a choice whether
 * to add another element (1) or to stop (0), followed by that element.
 *
 * Each element that a choice added is marked, together with that choice, as
 * a span of the kind SpanKind::Element, as is the choice that ends the
 * collection, so that deleting the span from the choice sequence deletes the
 * element. Each element that the minimum size requires is marked as a span
 * of the kind SpanKind::RequiredElement. This way, tools that change choice
 * sequences know where elements start and end, and which choices decide how
 * many of them there are.
 *
 * The choice to add another element is 1 with the same probability for each
 * element, so the sizes above the minimum size are distributed geometrically:
 * most collections are small, and a size that is much larger than the mean
 * size practically does not occur. So that a collection with a maximum size
 * reaches it nevertheless, as an integer reaches the maximum of its range,
 * some of the collections aim for the maximum size: when the first of these
 * choices of a collection is drawn at random, it is decided whether the
 * collection aims for its maximum size, and the choices of a collection that
 * does are 1 until one more element reaches the maximum size. Whether that
 * element is added is chosen like for any other collection, so that the size
 * next to the maximum size occurs as well. The fewer collections aim for the
 * maximum size the larger it is, so that the mean size of the collections
 * does not grow by more than a third.
 *
 * This only affects which choices are drawn at random, not what a choice
 * stands for: a choice sequence is replayed as the same collection as before.
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for sebastian/choice-sequence
 */
final readonly class Repetition
{
    public const float DEFAULT_PROBABILITY_OF_ANOTHER_ELEMENT = 0.8;
    private const int MAXIMUM_NUMBER_OF_DUPLICATE_ELEMENTS    = 10;

    /**
     * The largest share of the collections with a maximum size that aim for
     * it.
     */
    private const float MAXIMUM_PROBABILITY_OF_AIMING_FOR_MAXIMUM_SIZE = 0.05;

    /**
     * The share of their mean size by which the mean size of the collections
     * with a maximum size may grow because some of them aim for it.
     */
    private const float GROWTH_OF_MEAN_SIZE = 1 / 3;

    /**
     * @var non-negative-int
     */
    private int $minimumSize;

    /**
     * @var ?non-negative-int
     */
    private ?int $maximumSize;
    private BernoulliDistribution $anotherElement;
    private ExactDistribution $certainlyAnotherElement;
    private float $probabilityOfAimingForMaximumSize;

    /**
     * @var non-empty-string
     */
    private string $label;

    /**
     * @param non-empty-string  $label       the label of the spans of the elements (see Label)
     * @param non-negative-int  $minimumSize
     * @param ?non-negative-int $maximumSize
     */
    public function __construct(string $label, int $minimumSize, ?int $maximumSize, float $probabilityOfAnotherElement = self::DEFAULT_PROBABILITY_OF_ANOTHER_ELEMENT)
    {
        $this->label                             = $label;
        $this->minimumSize                       = $minimumSize;
        $this->maximumSize                       = $maximumSize;
        $this->anotherElement                    = new BernoulliDistribution($probabilityOfAnotherElement);
        $this->certainlyAnotherElement           = new ExactDistribution(1);
        $this->probabilityOfAimingForMaximumSize = self::probabilityOfAimingForMaximumSize($minimumSize, $maximumSize, $probabilityOfAnotherElement);
    }

    /**
     * @param Closure(): bool $element draws an element and returns whether it
     *                                 was added to the collection, which it is
     *                                 not when it duplicates an earlier element
     *
     * @throws TooManyChoicesException
     * @throws TooManyDuplicateElementsException when no element that is added to the collection is drawn in several attempts while the collection has fewer elements than its minimum size requires
     */
    public function repeat(ChoiceSource $source, Closure $element): void
    {
        $size               = 0;
        $duplicates         = 0;
        $aimsForMaximumSize = null;

        while ($this->maximumSize === null || $size < $this->maximumSize) {
            if ($size < $this->minimumSize) {
                $source->startSpan($this->label, SpanKind::RequiredElement);
            } else {
                $source->startSpan($this->label, SpanKind::Element);
            }

            if ($size >= $this->minimumSize) {
                if ($aimsForMaximumSize === null) {
                    $aimsForMaximumSize = $this->aimsForMaximumSize($source);
                }

                if ($source->draw(1, $this->anotherElement($size, $aimsForMaximumSize)) === 0) {
                    $source->endSpan();

                    return;
                }
            }

            $added = $element();

            $source->endSpan();

            if ($added) {
                $size++;

                $duplicates = 0;

                continue;
            }

            $duplicates++;

            if ($duplicates < self::MAXIMUM_NUMBER_OF_DUPLICATE_ELEMENTS) {
                continue;
            }

            if ($size < $this->minimumSize) {
                throw new TooManyDuplicateElementsException(self::MAXIMUM_NUMBER_OF_DUPLICATE_ELEMENTS);
            }

            return;
        }
    }

    /**
     * Decides, when choices are drawn at random, whether the collection whose
     * elements are about to be drawn aims for its maximum size. No choice is
     * drawn for this.
     */
    private function aimsForMaximumSize(ChoiceSource $source): bool
    {
        if ($this->probabilityOfAimingForMaximumSize === 0.0) {
            return false;
        }

        return $source->suggest([true], $this->probabilityOfAimingForMaximumSize) !== null;
    }

    /**
     * How the choice whether a collection of the given size gets another
     * element is drawn at random.
     *
     * @param non-negative-int $size
     */
    private function anotherElement(int $size, bool $aimsForMaximumSize): Distribution
    {
        if ($aimsForMaximumSize && $this->maximumSize !== null && $size < $this->maximumSize - 1) {
            return $this->certainlyAnotherElement;
        }

        return $this->anotherElement;
    }

    /**
     * The probability with which a collection aims for its maximum size. It
     * is so small that the mean size of the collections grows by at most
     * GROWTH_OF_MEAN_SIZE of what it is when none of them aims for its
     * maximum size, which is the minimum size plus the mean number of
     * elements that are added to it: a collection that aims for its maximum
     * size has at most $maximumSize - $minimumSize elements more.
     *
     * @param non-negative-int  $minimumSize
     * @param ?non-negative-int $maximumSize
     */
    private static function probabilityOfAimingForMaximumSize(int $minimumSize, ?int $maximumSize, float $probabilityOfAnotherElement): float
    {
        // without a maximum size, there is nothing to aim for, and aiming
        // for it only affects the choices before the one for the element
        // that reaches the maximum size
        if ($maximumSize === null || $minimumSize >= $maximumSize - 1) {
            return 0.0;
        }

        // a collection that never gets another element must not get one, and
        // one that always gets another element has its maximum size anyway
        if ($probabilityOfAnotherElement <= 0.0 || $probabilityOfAnotherElement >= 1.0) {
            return 0.0;
        }

        $meanSize = $minimumSize + $probabilityOfAnotherElement / (1.0 - $probabilityOfAnotherElement);

        return min(
            self::MAXIMUM_PROBABILITY_OF_AIMING_FOR_MAXIMUM_SIZE,
            self::GROWTH_OF_MEAN_SIZE * $meanSize / ($maximumSize - $minimumSize),
        );
    }
}
