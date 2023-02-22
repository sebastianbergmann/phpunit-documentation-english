<?php declare(strict_types=1);
final class Email
{
    private string $email;

    public function __construct(string $email)
    {
        $this->ensureIsValidEmail($email);

        $this->email = $email;
    }

    public function asString(): string
    {
        return $this->email;
    }

    public function equals(self $other): bool
    {
        return $this->asString() === $other->asString();
    }

    private function ensureIsValidEmail(string $email): void
    {
        // ...
    }
}
