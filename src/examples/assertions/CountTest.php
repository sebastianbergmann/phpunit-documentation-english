<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class CountTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertCount(0, ['foo']);
    }
}
