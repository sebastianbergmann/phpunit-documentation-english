<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class DeprecationExpectationTest extends TestCase
{
    public function testFailure(): void
    {
        $this->expectUserDeprecationMessage('the-deprecation-message');
    }
}
