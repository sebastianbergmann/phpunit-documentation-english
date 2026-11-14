<?php declare(strict_types=1);
final class Truck
{
    private int $capacity;
    private int $load = 0;

    public function __construct(int $capacity)
    {
        $this->capacity = $capacity;
    }

    public function load(int $weight): bool
    {
        if ($this->load >= $this->capacity) {
            return false;
        }

        $this->load += $weight;

        return true;
    }

    public function currentLoad(): int
    {
        return $this->load;
    }
}
