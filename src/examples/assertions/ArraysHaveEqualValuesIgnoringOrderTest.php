<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ArraysHaveEqualValuesIgnoringOrderTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertArraysHaveEqualValuesIgnoringOrder(
            [1, 2, 3],
            [3, 4, 1],
        );
    }
}
