<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class StringEqualsFileTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertStringEqualsFile(
            __DIR__ . '/expected.txt',
            'actual'
        );
    }
}
