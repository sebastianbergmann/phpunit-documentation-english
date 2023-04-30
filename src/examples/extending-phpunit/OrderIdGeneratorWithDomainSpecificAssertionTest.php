<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class OrderIdGeneratorWithDomainSpecificAssertionTest extends TestCase
{
    public function testGenerateGeneratesId(): void
    {
        $orderIdGenerator = new OrderIdGenerator;

        $orderId = $orderIdGenerator->generate();

        $this->assertStringIsOrderId($orderId);
    }

    private function assertStringIsOrderId(string $value): void
    {
        $this->assertMatchesRegularExpression(
            '/^[a-f0-9]{8}-[a-f0-9]{4}$/',
            $value,
            sprintf(
                'Failed asserting that "%s" is a valid order ID.',
                $value,
            )
        );
    }
}
