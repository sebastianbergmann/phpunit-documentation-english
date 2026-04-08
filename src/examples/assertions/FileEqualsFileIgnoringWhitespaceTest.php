<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class FileEqualsFileIgnoringWhitespaceTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertFileEqualsFileIgnoringWhitespace(
            __DIR__ . '/expected.txt',
            __DIR__ . '/actual.txt',
        );
    }
}
