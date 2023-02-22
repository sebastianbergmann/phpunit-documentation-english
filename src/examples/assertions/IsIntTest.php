<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class IsIntTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertIsInt(null);
    }
}
