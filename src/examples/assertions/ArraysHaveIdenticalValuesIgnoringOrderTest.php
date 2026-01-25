<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ArraysHaveIdenticalValuesIgnoringOrderTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertArraysHaveIdenticalValuesIgnoringOrder(
            [1, 2, 3],
            [3, '2', 1],
        );
    }
}
