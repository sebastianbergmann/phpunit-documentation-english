<?php declare(strict_types=1);
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\FromPool;
use PHPUnit\Framework\Attributes\StateMachineInvariant;
use PHPUnit\Framework\Attributes\StateMachineRule;

final class ParkingGarageMachine
{
    private ParkingGarage $garage;
    private int $capacity;
    private int $parkedCars = 0;

    public function __construct(ParkingGarage $garage, int $capacity)
    {
        $this->garage   = $garage;
        $this->capacity = $capacity;
    }

    #[StateMachineRule('hasFreeSpaces', 'tickets')]
    public function enter(): int
    {
        $ticket = $this->garage->enter();

        $this->parkedCars++;

        return $ticket;
    }

    #[StateMachineRule]
    public function leave(#[FromPool('tickets', true)] int $ticket): void
    {
        $this->garage->leave($ticket);

        $this->parkedCars--;
    }

    public function hasFreeSpaces(): bool
    {
        return $this->parkedCars < $this->capacity;
    }

    #[StateMachineInvariant]
    public function freeSpacesMatchTheNumberOfParkedCars(): void
    {
        Assert::assertSame(
            $this->capacity - $this->parkedCars,
            $this->garage->freeSpaces(),
        );
    }
}
