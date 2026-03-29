<?php declare(strict_types=1);
use SebastianBergmann\Comparator\Comparator;
use SebastianBergmann\Comparator\ComparisonFailure;

final class MoneyComparator extends Comparator
{
    public function accepts(mixed $expected, mixed $actual): bool
    {
        return $expected instanceof Money && $actual instanceof Money;
    }

    public function assertEquals(mixed $expected, mixed $actual, float $delta = 0.0, bool $canonicalize = false, bool $ignoreCase = false): void
    {
        if ($expected->amount() !== $actual->amount() ||
            $expected->currency() !== $actual->currency()) {
            throw new ComparisonFailure(
                $expected,
                $actual,
                $this->exporter()->export($expected),
                $this->exporter()->export($actual),
                'Failed asserting that two Money objects are equal.',
            );
        }
    }
}
