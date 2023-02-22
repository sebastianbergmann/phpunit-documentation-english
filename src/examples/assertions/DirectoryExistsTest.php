<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class DirectoryExistsTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertDirectoryExists('/path/to/directory');
    }
}
