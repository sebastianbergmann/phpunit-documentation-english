<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class IsArrayTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertIsArray(null);
    }
}
