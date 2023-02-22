<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class StringContainsStringTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertStringContainsString('foo', 'bar');
    }
}
