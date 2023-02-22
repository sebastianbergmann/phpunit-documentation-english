<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class IsCallableTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertIsCallable(null);
    }
}
