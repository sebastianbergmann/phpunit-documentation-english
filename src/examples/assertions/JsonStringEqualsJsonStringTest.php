<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class JsonStringEqualsJsonStringTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertJsonStringEqualsJsonString(
            json_encode(['mascot' => 'elePHPant']),
            json_encode(['mascot' => 'elephant']),
        );
    }
}
