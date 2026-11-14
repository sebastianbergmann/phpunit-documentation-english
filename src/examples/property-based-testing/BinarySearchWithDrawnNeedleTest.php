<?php declare(strict_types=1);
use PHPUnit\Framework\Attributes\Property;
use PHPUnit\Framework\Attributes\RequireClassification;
use PHPUnit\Framework\PropertyBased\Generators;
use PHPUnit\Framework\TestCase;

final class BinarySearchWithDrawnNeedleTest extends TestCase
{
    /**
     * @param non-empty-list<int> $values
     */
    #[Property]
    #[RequireClassification('needle is in the list', 25)]
    public function testFindsExactlyTheValuesThatAreInTheList(array $values): void
    {
        sort($values);

        $needle = $this->draw(
            Generators::oneOf(
                Generators::integers(),
                Generators::elements(...$values),
            ),
            'needle',
        );

        $expected = in_array($needle, $values, true);

        $this->classify($expected ? 'needle is in the list' : 'needle is not in the list');

        $this->assertSame($expected, BinarySearch::contains($values, $needle));
    }
}
