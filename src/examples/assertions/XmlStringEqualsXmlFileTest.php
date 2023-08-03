<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class XmlStringEqualsXmlFileTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertXmlStringEqualsXmlFile(
            __DIR__ . '/expected.xml',
            '<foo><baz/></foo>',
        );
    }
}
