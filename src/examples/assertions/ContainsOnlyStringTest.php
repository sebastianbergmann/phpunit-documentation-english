<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ContainsOnlyStringTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertContainsOnlyString([null]);
    }
}
