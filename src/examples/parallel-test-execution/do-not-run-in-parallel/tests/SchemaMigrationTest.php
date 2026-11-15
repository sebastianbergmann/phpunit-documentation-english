<?php declare(strict_types=1);
use PHPUnit\Framework\Attributes\DoNotRunInParallel;
use PHPUnit\Framework\TestCase;

#[DoNotRunInParallel]
final class SchemaMigrationTest extends TestCase
{
    public function testSchemaCanBeMigrated(): void
    {
        // Changes the schema of a database that other tests use
        $this->assertTrue(true);
    }
}
