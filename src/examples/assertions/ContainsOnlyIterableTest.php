<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ContainsOnlyIterableTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertContainsOnlyIterable([null]);
    }
}
