<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class SlugTest extends TestCase
{
    public function testReplacesWhitespaceWithHyphens(): void
    {
        $this->assertSame('test-impact-analysis', Slug::from('Test Impact Analysis'));
    }

    public function testRemovesPunctuation(): void
    {
        $this->assertSame('what-changed', Slug::from('What changed?'));
    }
}
