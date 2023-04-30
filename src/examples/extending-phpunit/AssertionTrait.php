<?php declare(strict_types=1);

trait AssertionTrait
{
    final protected function assertStringIsOrderId(string $value): void
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
