<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class IsObjectTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertIsObject(null);
    }
}
