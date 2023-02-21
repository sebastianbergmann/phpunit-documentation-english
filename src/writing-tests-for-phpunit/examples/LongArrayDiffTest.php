<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class LongArrayDiffTest extends TestCase
{
    public function testEquality(): void
    {
        $this->assertSame(
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 2,  3, 4, 5, 6],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 2, 33, 4, 5, 6]
        );
    }
}
