<?php declare(strict_types=1);
final readonly class Money
{
    private int $cents;

    public function __construct(int $cents)
    {
        $this->cents = $cents;
    }

    public function cents(): int
    {
        return $this->cents;
    }

    /**
     * @param positive-int $parts
     *
     * @return non-empty-list<Money>
     */
    public function split(int $parts): array
    {
        $share = intdiv($this->cents, $parts);

        return array_fill(0, $parts, new self($share));
    }
}
