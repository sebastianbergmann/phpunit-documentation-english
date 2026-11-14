<?php declare(strict_types=1);
final readonly class EmailAddress
{
    private string $address;

    public function __construct(string $address)
    {
        if (filter_var($address, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException(
                sprintf('"%s" is not a valid email address', $address),
            );
        }

        $this->address = $address;
    }

    public function asString(): string
    {
        return $this->address;
    }
}
