<?php declare(strict_types=1);
use PHPUnit\Framework\Attributes\Property;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

final class MoneySplitWithExplicitExampleTest extends TestCase
{
    /**
     * @param int<0, 100000> $cents
     * @param int<1, 100>    $parts
     */
    #[Property]
    #[TestWith([1, 2])]
    public function testPartsAddUpToTheWhole(int $cents, int $parts): void
    {
        $money = new Money($cents);

        $sum = 0;

        foreach ($money->split($parts) as $part) {
            $sum += $part->cents();
        }

        $this->assertSame($cents, $sum);
    }
}
