<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use SebastianBergmann\Comparator\Factory;

final class MoneyComparatorTest extends TestCase
{
    private MoneyComparator $comparator;

    protected function setUp(): void
    {
        $this->comparator = new MoneyComparator;

        Factory::getInstance()->register($this->comparator);
    }

    protected function tearDown(): void
    {
        Factory::getInstance()->unregister($this->comparator);
    }

    public function testMoneyObjectsWithSameAmountAndCurrencyAreEqual(): void
    {
        $this->assertEquals(
            new Money(100, 'EUR'),
            new Money(100, 'EUR'),
        );
    }
}
