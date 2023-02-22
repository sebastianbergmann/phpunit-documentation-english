<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class IsScalarTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertIsScalar(null);
    }
}
