<?php declare(strict_types=1);
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Property;
use PHPUnit\Framework\TestCase;

final class RunLengthEncodingRobustnessTest extends TestCase
{
    #[Property]
    #[DoesNotPerformAssertions]
    public function testAnyStringCanBeEncoded(string $string): void
    {
        RunLengthEncoding::encode($string);
    }
}
