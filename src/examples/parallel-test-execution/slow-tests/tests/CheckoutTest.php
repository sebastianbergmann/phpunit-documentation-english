<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class CheckoutTest extends TestCase
{
    public function testOrderCanBePlaced(): void
    {
        // Stands in for a test that takes half a second
        usleep(500000);

        $this->assertTrue(true);
    }

    public function testOrderCanBeCancelled(): void
    {
        usleep(500000);

        $this->assertTrue(true);
    }
}
