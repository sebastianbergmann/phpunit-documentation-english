<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class JsonFileEqualsJsonFileTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertJsonFileEqualsJsonFile(
            __DIR__ . '/expected.json',
            __DIR__ . '/actual.json',
        );
    }
}
