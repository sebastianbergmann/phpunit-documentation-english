<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class IsResourceTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertIsResource(null);
    }
}
