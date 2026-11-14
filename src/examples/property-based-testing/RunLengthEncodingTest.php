<?php declare(strict_types=1);
use PHPUnit\Framework\Attributes\Property;
use PHPUnit\Framework\TestCase;

final class RunLengthEncodingTest extends TestCase
{
    public function testEncodesRunsOfCharacters(): void
    {
        $this->assertSame('3a2b1c', RunLengthEncoding::encode('aaabbc'));
    }

    public function testDecodesRunsOfCharacters(): void
    {
        $this->assertSame('aaabbc', RunLengthEncoding::decode('3a2b1c'));
    }

    #[Property]
    public function testDecodingReversesEncoding(string $string): void
    {
        $this->assertSame(
            $string,
            RunLengthEncoding::decode(RunLengthEncoding::encode($string)),
        );
    }
}
