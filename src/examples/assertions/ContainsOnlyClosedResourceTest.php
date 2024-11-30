<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ContainsOnlyClosedResourceTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertContainsOnlyClosedResource([null]);
    }
}
