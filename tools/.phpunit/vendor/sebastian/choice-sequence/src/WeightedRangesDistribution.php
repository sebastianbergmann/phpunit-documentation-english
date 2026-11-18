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
use Random\IntervalBoundary;
use Random\Randomizer;

/**
 * Selects one of the given ranges of choices according to its weight and
 * draws a choice from it uniformly. Ranges that start above the maximum and
 * ranges without a weight are ignored, and ranges that extend beyond the
 * maximum are cut off. When no range is left, the choice is drawn uniformly
 * from all choices up to the maximum.
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for sebastian/choice-sequence
 */
final readonly class WeightedRangesDistribution implements Distribution
{
    /**
     * @var non-empty-list<array{weight: finite-float&non-negative-float, minimum: non-negative-int, maximum: non-negative-int}>
     */
    private array $ranges;

    /**
     * @param non-empty-list<array{weight: finite-float&non-negative-float, minimum: non-negative-int, maximum: non-negative-int}> $ranges the minimum of a range is not larger than its maximum
     *
     * @throws InvalidArgumentException when the minimum of a range is larger than its maximum
     */
    public function __construct(array $ranges)
    {
        foreach ($ranges as $range) {
            if ($range['minimum'] > $range['maximum']) {
                throw InvalidArgumentException::minimumOfRangeLargerThanMaximum($range['minimum'], $range['maximum']);
            }
        }

        $this->ranges = $ranges;
    }

    public function sample(Randomizer $randomizer, int $maximum): int
    {
        $ranges      = [];
        $totalWeight = 0.0;

        foreach ($this->ranges as $range) {
            if ($range['minimum'] > $maximum || $range['weight'] <= 0.0) {
                continue;
            }

            $ranges[] = $range;
            $totalWeight += $range['weight'];
        }

        if ($ranges === []) {
            return $randomizer->getInt(0, $maximum);
        }

        // scaling a float from the interval [0, 1) to this interval would
        // not draw every float of this interval with the same probability
        $selector = $randomizer->getFloat(0.0, $totalWeight, IntervalBoundary::ClosedOpen);

        // the weights are added up in the order in which they were added up
        // for the total weight, so that the last sum is the total weight
        // exactly, which the selector is less than: subtracting the weights
        // from the selector instead can leave it at zero or slightly above
        // because of rounding errors, so that no range would be selected
        $selected         = $ranges[0];
        $cumulativeWeight = 0.0;

        foreach ($ranges as $range) {
            $selected = $range;
            $cumulativeWeight += $range['weight'];

            if ($selector < $cumulativeWeight) {
                break;
            }
        }

        return $randomizer->getInt($selected['minimum'], min($selected['maximum'], $maximum));
    }
}
