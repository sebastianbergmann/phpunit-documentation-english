<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ArraysAreEqualIgnoringOrderTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertArraysAreEqualIgnoringOrder(
            ['a' => 1, 'b' => 2],
            ['b' => 2, 'a' => 3],
        );
    }
}
