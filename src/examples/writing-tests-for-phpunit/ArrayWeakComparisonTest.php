<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ArrayWeakComparisonTest extends TestCase
{
    public function testEquality(): void
    {
        $this->assertEquals(
            [1, 2, 3, 4, 5, 6],
            ['1', 2, 33, 4, 5, 6]
        );
    }
}
