<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ContainsOnlyScalarTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertContainsOnlyScalar([null]);
    }
}
