<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class XmlStringEqualsXmlFileConsideringCommentsTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertXmlStringEqualsXmlFileConsideringComments(
            __DIR__ . '/with-comments.xml',
            '<root><node/></root>',
        );
    }
}
