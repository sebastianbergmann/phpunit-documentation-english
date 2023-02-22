<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class IsStringTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertIsString(null);
    }
}
