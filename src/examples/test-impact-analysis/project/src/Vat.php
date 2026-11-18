<?php declare(strict_types=1);
final class Vat
{
    private int $rate;

    public function __construct(int $rate)
    {
        $this->rate = $rate;
    }

    public function of(int $netAmount): int
    {
        return intdiv($netAmount * $this->rate, 100);
    }
}
