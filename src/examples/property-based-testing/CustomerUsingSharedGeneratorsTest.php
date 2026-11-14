<?php declare(strict_types=1);
use PHPUnit\Framework\Attributes\Property;
use PHPUnit\Framework\Attributes\UseGenerators;
use PHPUnit\Framework\TestCase;

#[UseGenerators(DomainGenerators::class)]
final class CustomerUsingSharedGeneratorsTest extends TestCase
{
    #[Property]
    public function testEmailAddressCanBeChanged(Customer $customer, EmailAddress $newAddress): void
    {
        $customer->changeEmailAddress($newAddress);

        $this->assertSame($newAddress, $customer->emailAddress());
    }
}
