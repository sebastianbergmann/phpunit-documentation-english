<?php declare(strict_types=1);
use PHPUnit\Framework\Attributes\Property;
use PHPUnit\Framework\TestCase;

final class EmailAddressByAssumptionTest extends TestCase
{
    #[Property]
    public function testCanBeConvertedToString(string $address): void
    {
        $this->assume(filter_var($address, FILTER_VALIDATE_EMAIL) !== false);

        $this->assertSame($address, new EmailAddress($address)->asString());
    }
}
