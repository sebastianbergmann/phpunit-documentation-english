<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class StringMatchesFormatFileTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertStringMatchesFormatFile(
            __DIR__ . '/expected-format.txt',
            'foo'
        );
    }
}
