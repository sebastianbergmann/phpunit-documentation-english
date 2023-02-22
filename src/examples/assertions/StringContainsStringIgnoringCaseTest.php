<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class StringContainsStringIgnoringCaseTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertStringContainsStringIgnoringCase('foo', 'bar');
    }
}
