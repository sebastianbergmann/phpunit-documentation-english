<?php declare(strict_types=1);
use PHPUnit\Framework\Attributes\CounterexampleFormatter;
use PHPUnit\Framework\Attributes\Property;
use PHPUnit\Framework\TestCase;

final class RunLengthEncodingWithFormatterTest extends TestCase
{
    public static function encoded(string $string): string
    {
        return 'encoded: ' . RunLengthEncoding::encode($string);
    }

    #[Property]
    #[CounterexampleFormatter('encoded')]
    public function testDecodingReversesEncoding(string $string): void
    {
        $this->assertSame(
            $string,
            RunLengthEncoding::decode(RunLengthEncoding::encode($string)),
        );
    }
}
