<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class StringEqualsStringIgnoringWhitespaceTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertStringEqualsStringIgnoringWhitespace(
            'hello world',
            'goodbye world',
        );
    }
}
