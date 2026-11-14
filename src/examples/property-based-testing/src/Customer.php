<?php declare(strict_types=1);
final class Customer
{
    private string $name;
    private EmailAddress $emailAddress;

    /**
     * @param non-empty-string $name
     */
    public function __construct(string $name, EmailAddress $emailAddress)
    {
        $this->name         = $name;
        $this->emailAddress = $emailAddress;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function emailAddress(): EmailAddress
    {
        return $this->emailAddress;
    }

    public function changeEmailAddress(EmailAddress $emailAddress): void
    {
        $this->emailAddress = $emailAddress;
    }
}
