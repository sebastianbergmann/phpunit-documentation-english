<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class GreaterThanTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertGreaterThan(2, 1);
    }
}
