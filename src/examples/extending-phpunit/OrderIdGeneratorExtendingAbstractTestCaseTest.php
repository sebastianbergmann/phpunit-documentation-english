<?php declare(strict_types=1);

final class OrderIdGeneratorExtendingAbstractTestCaseTest extends AbstractTestCase
{
    public function testGenerateGeneratesId(): void
    {
        $orderIdGenerator = new OrderIdGenerator;

        $orderId = $orderIdGenerator->generate();

        $this->assertStringIsOrderId($orderId);
    }
}
