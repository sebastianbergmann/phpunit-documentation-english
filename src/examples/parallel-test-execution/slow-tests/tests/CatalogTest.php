<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class CatalogTest extends TestCase
{
    public function testProductsAreListed(): void
    {
        // Stands in for a test that takes half a second
        usleep(500000);

        $this->assertTrue(true);
    }

    public function testProductsCanBeFiltered(): void
    {
        usleep(500000);

        $this->assertTrue(true);
    }
}
