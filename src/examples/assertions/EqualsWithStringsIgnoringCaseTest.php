<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class EqualsWithStringsIgnoringCaseTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertEqualsIgnoringCase('foo', 'BAR');
    }
}
