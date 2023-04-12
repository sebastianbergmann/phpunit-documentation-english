<?php declare(strict_types=1);
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

final class NumericDataSetsTestUsingExternalDataProvider extends TestCase
{
    #[DataProviderExternal(ExternalDataProvider::class, 'additionProvider')]
    public function testAdd(int $a, int $b, int $expected): void
    {
        $this->assertSame($expected, $a + $b);
    }
}

final class ExternalDataProvider
{
    public static function additionProvider(): array
    {
        return [
            [0, 0, 0],
            [0, 1, 1],
            [1, 0, 1],
            [1, 1, 3],
        ];
    }
}
