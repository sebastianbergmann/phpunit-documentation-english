<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class NullTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertNull('foo');
    }
}
