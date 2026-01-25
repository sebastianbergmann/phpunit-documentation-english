<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ArraysAreIdenticalTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertArraysAreIdentical(
            ['a' => 1, 'b' => 2],
            ['a' => 1, 'b' => '2'],
        );
    }
}
