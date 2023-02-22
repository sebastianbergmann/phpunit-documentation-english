<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class EqualsTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertEquals(1, 0);
    }

    public function testFailure2(): void
    {
        $this->assertEquals('bar', 'baz');
    }

    public function testFailure3(): void
    {
        $this->assertEquals("foo\nbar\nbaz\n", "foo\nbah\nbaz\n");
    }
}
