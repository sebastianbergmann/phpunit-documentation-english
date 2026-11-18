<?php declare(strict_types=1);
final class Invoice
{
    private Vat $vat;

    /**
     * @var list<int>
     */
    private array $netAmounts = [];

    public function __construct(Vat $vat)
    {
        $this->vat = $vat;
    }

    public function add(int $netAmount): void
    {
        $this->netAmounts[] = $netAmount;
    }

    public function total(): int
    {
        $netAmount = array_sum($this->netAmounts);

        return $netAmount + $this->vat->of($netAmount);
    }
}
