<?php declare(strict_types=1);
final class BinarySearch
{
    /**
     * @param list<int> $sortedValues
     */
    public static function contains(array $sortedValues, int $needle): bool
    {
        $low  = 0;
        $high = count($sortedValues) - 1;

        while ($low <= $high) {
            $middle = intdiv($low + $high, 2);

            if ($sortedValues[$middle] === $needle) {
                return true;
            }

            if ($sortedValues[$middle] < $needle) {
                $low = $middle + 1;
            } else {
                $high = $middle - 1;
            }
        }

        return false;
    }
}
