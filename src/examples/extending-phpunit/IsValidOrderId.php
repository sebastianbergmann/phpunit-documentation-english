<?php declare(strict_types=1);
use PHPUnit\Framework\Constraint\Constraint;

final class IsValidOrderId extends Constraint
{
    public function toString(): string
    {
        return 'is a valid order ID';
    }

    protected function matches(mixed $other): bool
    {
        return is_string($other)
            && preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}$/', $other) === 1;
    }
}
