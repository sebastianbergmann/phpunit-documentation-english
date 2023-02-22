<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class SameSizeTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertSameSize([1, 2], [1]);
    }
}
