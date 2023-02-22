<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ArrayHasKeyTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertArrayHasKey('foo', ['bar' => 'baz']);
    }
}
