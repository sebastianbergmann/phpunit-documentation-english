<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class PasswordTest extends TestCase
{
    public function testPasswordIsHashed(): void
    {
        $hash = password_hash('secret', PASSWORD_DEFAULT);

        $this->assertTrue(password_verify('secret', $hash));
    }
}
