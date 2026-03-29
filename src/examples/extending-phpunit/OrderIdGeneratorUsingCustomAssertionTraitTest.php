<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class OrderIdGeneratorUsingCustomAssertionTraitTest extends TestCase
{
    use CustomAssertionTrait;

    public function testGenerateGeneratesId(): void
    {
        $orderIdGenerator = new OrderIdGenerator;

        $orderId = $orderIdGenerator->generate();

        $this->assertStringIsOrderId($orderId);
    }
}
