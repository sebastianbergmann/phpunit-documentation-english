<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class SameWithMixedTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertSame('2204', 2204);
    }
}
