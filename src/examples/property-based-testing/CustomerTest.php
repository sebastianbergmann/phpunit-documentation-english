<?php declare(strict_types=1);
use PHPUnit\Framework\Attributes\GeneratorFor;
use PHPUnit\Framework\Attributes\Property;
use PHPUnit\Framework\PropertyBased\Generators;
use PHPUnit\Framework\PropertyBased\ValueGenerator;
use PHPUnit\Framework\TestCase;

final class CustomerTest extends TestCase
{
    /**
     * @return ValueGenerator<EmailAddress>
     */
    #[GeneratorFor(EmailAddress::class)]
    public static function emailAddresses(): ValueGenerator
    {
        return Generators::emailAddresses()->map(
            static fn (string $address): EmailAddress => new EmailAddress($address),
        );
    }

    #[Property]
    public function testEmailAddressCanBeChanged(Customer $customer, EmailAddress $newAddress): void
    {
        $customer->changeEmailAddress($newAddress);

        $this->assertSame($newAddress, $customer->emailAddress());
    }
}
