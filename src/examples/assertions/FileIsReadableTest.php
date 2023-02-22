<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class FileIsReadableTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertFileIsReadable('/path/to/file');
    }
}
