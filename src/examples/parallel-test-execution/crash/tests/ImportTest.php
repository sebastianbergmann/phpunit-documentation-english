<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ImportTest extends TestCase
{
    public function testEmptyFileCanBeImported(): void
    {
        $this->assertTrue(true);
    }

    public function testInvalidFileIsRejected(): void
    {
        // Stands in for tested code that ends the PHP process
        exit(1);
    }

    public function testValidFileCanBeImported(): void
    {
        $this->assertTrue(true);
    }
}
