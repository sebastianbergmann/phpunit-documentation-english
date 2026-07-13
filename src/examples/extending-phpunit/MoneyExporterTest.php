<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class MoneyExporterTest extends TestCase
{
    public function testArrayContainsMoney(): void
    {
        $this->registerObjectExporter(new MoneyExporter);

        $this->assertContains(
            new Money(100, 'EUR'),
            [new Money(100, 'USD')],
        );
    }
}
