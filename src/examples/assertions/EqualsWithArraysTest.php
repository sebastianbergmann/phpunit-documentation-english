<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class EqualsWithArraysTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertEquals(['a', 'b', 'c'], ['a', 'c', 'd']);
    }
}
