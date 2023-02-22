<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class XmlFileEqualsXmlFileTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertXmlFileEqualsXmlFile(
            __DIR__ . '/expected.xml',
            __DIR__ . '/actual.xml',
        );
    }
}
