<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class IsValidOrderIdTest extends TestCase
{
    public function testGenerateGeneratesId(): void
    {
        $orderIdGenerator = new OrderIdGenerator;

        $orderId = $orderIdGenerator->generate();

        $this->assertThat($orderId, new IsValidOrderId);
    }
}
