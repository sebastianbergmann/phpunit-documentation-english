<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class SearchTest extends TestCase
{
    public function testProductsCanBeFoundByName(): void
    {
        // Stands in for a test that takes half a second
        usleep(500000);

        $this->assertTrue(true);
    }

    public function testProductsCanBeFoundByDescription(): void
    {
        usleep(500000);

        $this->assertTrue(true);
    }
}
