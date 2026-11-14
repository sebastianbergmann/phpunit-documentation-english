<?php declare(strict_types=1);
final class ParkingGarage
{
    private int $capacity;

    /**
     * @var array<int, true>
     */
    private array $tickets = [];

    public function __construct(int $capacity)
    {
        $this->capacity = $capacity;
    }

    public function isFull(): bool
    {
        return $this->freeSpaces() === 0;
    }

    public function freeSpaces(): int
    {
        return $this->capacity - count($this->tickets);
    }

    public function enter(): int
    {
        if ($this->isFull()) {
            throw new RuntimeException('The parking garage is full');
        }

        $ticket = count($this->tickets) + 1;

        $this->tickets[$ticket] = true;

        return $ticket;
    }

    public function leave(int $ticket): void
    {
        if (!isset($this->tickets[$ticket])) {
            throw new RuntimeException('This ticket is not valid');
        }

        unset($this->tickets[$ticket]);
    }
}
