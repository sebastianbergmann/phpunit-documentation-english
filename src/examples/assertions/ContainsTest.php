<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ContainsTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertContains(4, [1, 2, 3]);
    }
}
