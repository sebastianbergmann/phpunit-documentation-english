<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class InvoiceTest extends TestCase
{
    public function testTotalOfInvoiceWithoutItemsIsZero(): void
    {
        $invoice = new Invoice(new Vat(19));

        $this->assertSame(0, $invoice->total());
    }

    public function testTotalIncludesVat(): void
    {
        $invoice = new Invoice(new Vat(19));

        $invoice->add(100);
        $invoice->add(200);

        $this->assertSame(357, $invoice->total());
    }
}
