<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ContainsOnlyNullTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertContainsOnlyNull(['string']);
    }
}
