<?php declare(strict_types=1);
final class Email
{
    private string $email;

    public static function fromString(string $email): self
    {
        return new self($email);
    }

    private function __construct(string $email)
    {
        $this->ensureIsValidEmail($email);

        $this->email = $email;
    }

    public function asString(): string
    {
        return $this->email;
    }

    private function ensureIsValidEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(
                sprintf(
                    '"%s" is not a valid email address',
                    $email,
                ),
            );
        }
    }
}
