<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class JsonStringEqualsJsonFileTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertJsonStringEqualsJsonFile(
            __DIR__ . '/expected.json',
            json_encode(['mascot' => 'elephant']),
        );
    }
}
