<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ArraysHaveEqualValuesTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertArraysHaveEqualValues(
            [1, 2, 3],
            [1, 4, 3],
        );
    }
}
