<?php declare(strict_types=1);
use PHPUnit\Framework\Attributes\Property;
use PHPUnit\Framework\TestCase;

final class CustomerWithoutGeneratorTest extends TestCase
{
    #[Property]
    public function testEmailAddressCanBeChanged(Customer $customer, EmailAddress $newAddress): void
    {
        $customer->changeEmailAddress($newAddress);

        $this->assertSame($newAddress, $customer->emailAddress());
    }
}
