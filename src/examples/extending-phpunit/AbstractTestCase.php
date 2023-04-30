<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
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
