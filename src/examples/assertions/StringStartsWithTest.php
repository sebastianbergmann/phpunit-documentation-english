<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class StringStartsWithTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertStringStartsWith('prefix', 'foo');
    }
}
