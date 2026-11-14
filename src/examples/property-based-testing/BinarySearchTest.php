<?php declare(strict_types=1);
use PHPUnit\Framework\Attributes\Property;
use PHPUnit\Framework\TestCase;

final class BinarySearchTest extends TestCase
{
    /**
     * @param list<int> $values
     */
    #[Property]
    public function testFindsExactlyTheValuesThatAreInTheList(array $values, int $needle): void
    {
        sort($values);

        $expected = in_array($needle, $values, true);

        $this->classify($expected ? 'needle is in the list' : 'needle is not in the list');
        $this->observe('size of the list', count($values));

        $this->assertSame($expected, BinarySearch::contains($values, $needle));
    }
}
