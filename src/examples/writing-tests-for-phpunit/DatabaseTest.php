<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class DatabaseTest extends TestCase
{
    protected function setUp(): void
    {
        if (!extension_loaded('pgsql')) {
            $this->markTestSkipped(
                'The PostgreSQL extension is not available',
            );
        }
    }

    public function testConnection(): void
    {
        // ...
    }
}
