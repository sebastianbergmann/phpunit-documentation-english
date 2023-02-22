<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class IsIterableTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertIsIterable(null);
    }
}
