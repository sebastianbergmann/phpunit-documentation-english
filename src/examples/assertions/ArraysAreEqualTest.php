<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ArraysAreEqualTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertArraysAreEqual(
            ['a' => 1, 'b' => 2],
            ['a' => 1, 'b' => 3],
        );
    }
}
