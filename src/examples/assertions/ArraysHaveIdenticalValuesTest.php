<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ArraysHaveIdenticalValuesTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertArraysHaveIdenticalValues(
            [1, 2, 3],
            [1, '2', 3],
        );
    }
}
