<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class OrderIdGeneratorUsingAssertionTraitTest extends TestCase
{
    use AssertionTrait;

    public function testGenerateGeneratesId(): void
    {
        $orderIdGenerator = new OrderIdGenerator;

        $orderId = $orderIdGenerator->generate();

        $this->assertStringIsOrderId($orderId);
    }
}
