<?php declare(strict_types=1);
final class ShippingCost
{
    public static function inCents(ParcelSize $size, bool $express): int
    {
        $cost = match ($size) {
            ParcelSize::Small  => 490,
            ParcelSize::Medium => 690,
            ParcelSize::Large  => 1090,
        };

        if ($express) {
            $cost += 500;
        }

        return $cost;
    }
}
