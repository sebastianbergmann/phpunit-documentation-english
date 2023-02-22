<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class IsFloatTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertIsFloat(null);
    }
}
