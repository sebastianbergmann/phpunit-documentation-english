<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class EqualsWithFloatsAndDeltaTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertEqualsWithDelta(1.0, 1.5, 0.1);
    }
}
