<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ContainsOnlyArrayTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertContainsOnlyArray([null]);
    }
}
