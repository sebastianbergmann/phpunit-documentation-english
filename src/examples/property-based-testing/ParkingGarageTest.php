<?php declare(strict_types=1);
use PHPUnit\Framework\Attributes\Property;
use PHPUnit\Framework\TestCase;

final class ParkingGarageTest extends TestCase
{
    public function testCarsCanEnterAndLeave(): void
    {
        $garage = new ParkingGarage(2);

        $first  = $garage->enter();
        $second = $garage->enter();

        $this->assertTrue($garage->isFull());

        $garage->leave($second);
        $garage->leave($first);

        $this->assertSame(2, $garage->freeSpaces());
    }

    #[Property]
    public function testKeepsTrackOfItsFreeSpaces(): void
    {
        $this->runStateMachine(
            new ParkingGarageMachine(new ParkingGarage(5), 5),
        );
    }
}
