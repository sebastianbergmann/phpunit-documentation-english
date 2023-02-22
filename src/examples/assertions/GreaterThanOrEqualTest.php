<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class GreaterThanOrEqualTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertGreaterThanOrEqual(2, 1);
    }
}
