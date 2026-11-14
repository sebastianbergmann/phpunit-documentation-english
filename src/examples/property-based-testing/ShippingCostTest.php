<?php declare(strict_types=1);
use PHPUnit\Framework\Attributes\Property;
use PHPUnit\Framework\TestCase;

final class ShippingCostTest extends TestCase
{
    #[Property]
    public function testShippingIsNeverFree(ParcelSize $size, bool $express): void
    {
        $this->assertGreaterThan(0, ShippingCost::inCents($size, $express));
    }
}
