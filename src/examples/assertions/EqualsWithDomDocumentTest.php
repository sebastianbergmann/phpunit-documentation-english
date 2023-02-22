<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class EqualsWithDomDocumentTest extends TestCase
{
    public function testFailure(): void
    {
        $expected = new DOMDocument;
        $expected->loadXML('<foo><bar/></foo>');

        $actual = new DOMDocument;
        $actual->loadXML('<bar><foo/></bar>');

        $this->assertEquals($expected, $actual);
    }
}
