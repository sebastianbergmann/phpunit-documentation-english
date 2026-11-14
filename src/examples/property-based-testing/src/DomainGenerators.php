<?php declare(strict_types=1);
use PHPUnit\Framework\Attributes\GeneratorFor;
use PHPUnit\Framework\PropertyBased\Generators;
use PHPUnit\Framework\PropertyBased\ValueGenerator;

final class DomainGenerators
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
}
