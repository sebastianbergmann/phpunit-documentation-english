<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class StringMatchesFormatTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertStringMatchesFormat('%i', 'foo');
    }
}
