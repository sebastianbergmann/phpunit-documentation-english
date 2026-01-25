<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ArraysAreIdenticalIgnoringOrderTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertArraysAreIdenticalIgnoringOrder(
            ['a' => 1, 'b' => 2],
            ['b' => 2, 'a' => '1'],
        );
    }
}
