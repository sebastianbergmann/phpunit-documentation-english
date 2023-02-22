<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ContainsOnlyTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertContainsOnly('string', ['1', '2', 3]);
    }
}
