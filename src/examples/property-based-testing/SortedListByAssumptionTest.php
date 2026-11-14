<?php declare(strict_types=1);
use PHPUnit\Framework\Attributes\Property;
use PHPUnit\Framework\TestCase;

final class SortedListByAssumptionTest extends TestCase
{
    /**
     * @param list<int> $values
     */
    #[Property]
    public function testFindsTheFirstValue(array $values): void
    {
        $sorted = $values;

        sort($sorted);

        $this->assume($values !== [] && $values === $sorted);

        $this->observe('size of the list', count($values));

        $this->assertTrue(BinarySearch::contains($values, $values[0]));
    }
}
