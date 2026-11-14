<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class MoneyTest extends TestCase
{
    public function testCanBeSplitIntoEqualParts(): void
    {
        $money = new Money(100);

        $parts = $money->split(2);

        $this->assertCount(2, $parts);
        $this->assertSame(50, $parts[0]->cents());
        $this->assertSame(50, $parts[1]->cents());
    }
}
