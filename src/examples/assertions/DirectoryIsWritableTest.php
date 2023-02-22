<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class DirectoryIsWritableTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertDirectoryIsWritable('/path/to/directory');
    }
}
