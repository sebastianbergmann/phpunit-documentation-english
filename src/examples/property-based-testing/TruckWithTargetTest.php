<?php declare(strict_types=1);
use PHPUnit\Framework\Attributes\Property;
use PHPUnit\Framework\TestCase;

final class TruckWithTargetTest extends TestCase
{
    /**
     * @param list<int<1, 100>> $parcels
     */
    #[Property(examples: 300)]
    public function testIsNeverOverloaded(array $parcels): void
    {
        $truck = new Truck(1000);

        foreach ($parcels as $weight) {
            $truck->load($weight);
        }

        $this->target('load', $truck->currentLoad());

        $this->assertLessThanOrEqual(1000, $truck->currentLoad());
    }
}
