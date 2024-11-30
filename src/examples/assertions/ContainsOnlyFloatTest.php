<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ContainsOnlyFloatTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertContainsOnlyFloat([null]);
    }
}
