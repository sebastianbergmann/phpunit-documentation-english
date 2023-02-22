<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class InstanceOfTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertInstanceOf(RuntimeException::class, new Exception);
    }
}
