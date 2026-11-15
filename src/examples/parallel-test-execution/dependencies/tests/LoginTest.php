<?php declare(strict_types=1);
use PHPUnit\Framework\Attributes\DependsExternal;
use PHPUnit\Framework\TestCase;

final class LoginTest extends TestCase
{
    #[DependsExternal(RegistrationTest::class, 'testUserCanRegister')]
    public function testRegisteredUserCanLogIn(string $emailAddress): void
    {
        $this->assertSame('user@example.org', $emailAddress);
    }
}
