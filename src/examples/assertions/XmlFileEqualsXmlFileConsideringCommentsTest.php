<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class XmlFileEqualsXmlFileConsideringCommentsTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertXmlFileEqualsXmlFileConsideringComments(
            __DIR__ . '/with-comments.xml',
            __DIR__ . '/without-comments.xml',
        );
    }
}
