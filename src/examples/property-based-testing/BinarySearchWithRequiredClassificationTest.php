<?php declare(strict_types=1);
use PHPUnit\Framework\Attributes\Property;
use PHPUnit\Framework\Attributes\RequireClassification;
use PHPUnit\Framework\TestCase;

final class BinarySearchWithRequiredClassificationTest extends TestCase
{
    /**
     * @param list<int> $values
     */
    #[Property]
    #[RequireClassification('needle is in the list', 25)]
    public function testFindsExactlyTheValuesThatAreInTheList(array $values, int $needle): void
    {
        sort($values);

        $expected = in_array($needle, $values, true);

        $this->classify($expected ? 'needle is in the list' : 'needle is not in the list');

        $this->assertSame($expected, BinarySearch::contains($values, $needle));
    }
}
