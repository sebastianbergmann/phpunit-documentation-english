<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class FailedAssertionTest extends TestCase
{
    public function testAssertionsAfterAFailedAssertionAreNotExecuted(): void
    {
        $this->assertSame(1, 1);
        $this->assertSame(2, 3);
        $this->assertSame(4, 5);
    }
}
