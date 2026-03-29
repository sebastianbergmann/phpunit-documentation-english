<?php declare(strict_types=1);

trait CustomAssertionTrait
{
    final protected static function assertStringIsOrderId(string $value, string $message = ''): void
    {
        static::assertThat($value, new IsValidOrderId, $message);
    }
}
