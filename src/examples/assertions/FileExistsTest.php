<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class FileExistsTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertFileExists('/path/to/file');
    }
}
