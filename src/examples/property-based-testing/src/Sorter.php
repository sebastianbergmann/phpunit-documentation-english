<?php declare(strict_types=1);
final class Sorter
{
    /**
     * @param list<int> $values
     *
     * @return list<int>
     */
    public static function sort(array $values): array
    {
        $count = count($values);

        for ($i = 1; $i < $count; $i++) {
            $value = $values[$i];

            for ($j = $i - 1; $j >= 0 && $values[$j] > $value; $j--) {
                $values[$j + 1] = $values[$j];
            }

            $values[$j + 1] = $value;
        }

        return $values;
    }
}
