<?php declare(strict_types=1);
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class EmailDataProviderTest extends TestCase
{
    public static function validEmailAddressProvider(): array
    {
        return [
            ['user@example.org'],
            ['user.name@example.org'],
            ['user+tag@example.org'],
            ['user@mail.example.org'],
        ];
    }

    #[DataProvider('validEmailAddressProvider')]
    public function testCanBeCreatedFromValidEmail(string $string): void
    {
        $email = Email::fromString($string);

        $this->assertSame($string, $email->asString());
    }
}
