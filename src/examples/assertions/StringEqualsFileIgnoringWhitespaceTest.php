<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class StringEqualsFileIgnoringWhitespaceTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertStringEqualsFileIgnoringWhitespace(
            __DIR__ . '/expected.txt',
            'actual',
        );
    }
}
