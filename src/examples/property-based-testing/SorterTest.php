<?php declare(strict_types=1);
use PHPUnit\Framework\Attributes\Property;
use PHPUnit\Framework\TestCase;

final class SorterTest extends TestCase
{
    /**
     * @param list<int> $values
     */
    #[Property]
    public function testResultIsOrdered(array $values): void
    {
        $sorted = Sorter::sort($values);

        $this->assertCount(count($values), $sorted);

        for ($i = 1; $i < count($sorted); $i++) {
            $this->assertLessThanOrEqual($sorted[$i], $sorted[$i - 1]);
        }
    }

    /**
     * @param list<int> $values
     */
    #[Property]
    public function testResultHasTheSameElementsAsTheInput(array $values): void
    {
        $this->assertEqualsCanonicalizing($values, Sorter::sort($values));
    }

    /**
     * @param list<int> $values
     */
    #[Property]
    public function testSortingTwiceIsTheSameAsSortingOnce(array $values): void
    {
        $sorted = Sorter::sort($values);

        $this->assertSame($sorted, Sorter::sort($sorted));
    }

    /**
     * @param list<int> $values
     */
    #[Property]
    public function testOrderOfTheInputDoesNotMatter(array $values): void
    {
        $this->assertSame(
            Sorter::sort($values),
            Sorter::sort(array_reverse($values)),
        );
    }

    /**
     * @param list<int> $values
     */
    #[Property]
    public function testAgreesWithTheSortFunctionOfPhp(array $values): void
    {
        $expected = $values;

        sort($expected);

        $this->assertSame($expected, Sorter::sort($values));
    }
}
