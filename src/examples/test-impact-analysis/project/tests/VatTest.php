<?php declare(strict_types=1);
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesFixture;
use PHPUnit\Framework\TestCase;

final class VatTest extends TestCase
{
    /**
     * @return list<array{int, int, int}>
     */
    #[UsesFixture('fixtures/vat.csv')]
    public static function amounts(): array
    {
        $amounts = [];

        foreach (file(__DIR__ . '/fixtures/vat.csv', FILE_IGNORE_NEW_LINES) as $line) {
            [$rate, $netAmount, $vat] = explode(',', $line);

            $amounts[] = [(int) $rate, (int) $netAmount, (int) $vat];
        }

        return $amounts;
    }

    #[DataProvider('amounts')]
    public function testCalculatesVat(int $rate, int $netAmount, int $expected): void
    {
        $this->assertSame($expected, (new Vat($rate))->of($netAmount));
    }
}
