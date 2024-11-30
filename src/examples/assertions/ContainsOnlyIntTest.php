<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ContainsOnlyIntTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertContainsOnlyInt([null]);
    }
}
