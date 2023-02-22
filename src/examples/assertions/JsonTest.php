<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class JsonTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertJson('not-a-json-string');
    }
}
