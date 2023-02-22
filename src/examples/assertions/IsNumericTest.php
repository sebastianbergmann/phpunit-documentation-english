<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class IsNumericTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertIsNumeric(null);
    }
}
