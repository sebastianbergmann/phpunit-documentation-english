<?php declare(strict_types=1);
use PHPUnit\Framework\Attributes\ForAll;
use PHPUnit\Framework\Attributes\Property;
use PHPUnit\Framework\PropertyBased\Generators;
use PHPUnit\Framework\PropertyBased\ValueGenerator;
use PHPUnit\Framework\TestCase;

final class SortedListByConstructionTest extends TestCase
{
    /**
     * @return ValueGenerator<non-empty-list<int>>
     */
    public static function sortedLists(): ValueGenerator
    {
        return Generators::lists(Generators::integers(), minimumSize: 1)->map(
            static function (array $values): array
            {
                sort($values);

                return $values;
            },
        );
    }

    /**
     * @param non-empty-list<int> $values
     */
    #[Property]
    public function testFindsTheFirstValue(#[ForAll('sortedLists')] array $values): void
    {
        $this->observe('size of the list', count($values));

        $this->assertTrue(BinarySearch::contains($values, $values[0]));
    }
}
