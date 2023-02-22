<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class InfiniteTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertInfinite(1);
    }
}
