<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class DirectoryIsReadableTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertDirectoryIsReadable('/path/to/directory');
    }
}
