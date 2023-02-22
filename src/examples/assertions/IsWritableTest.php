<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class IsWritableTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertIsWritable('/path/to/unwritable');
    }
}
