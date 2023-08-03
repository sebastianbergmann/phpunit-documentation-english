<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class XmlStringEqualsXmlStringTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertXmlStringEqualsXmlString(
            '<foo><bar/></foo>',
            '<foo><baz/></foo>',
        );
    }
}
