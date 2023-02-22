<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class FileEqualsTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertFileEquals(
            __DIR__ . '/expected.txt',
            __DIR__ . '/actual.txt'
        );
    }
}
