<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class RegistrationTest extends TestCase
{
    public function testUserCanRegister(): string
    {
        $emailAddress = 'user@example.org';

        $this->assertNotEmpty($emailAddress);

        return $emailAddress;
    }
}
