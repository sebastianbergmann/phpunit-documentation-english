<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class XmlStringEqualsXmlStringConsideringCommentsTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertXmlStringEqualsXmlStringConsideringComments(
            '<root><!-- a comment --><node/></root>',
            '<root><node/></root>',
        );
    }
}
