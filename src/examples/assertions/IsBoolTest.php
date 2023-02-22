<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class IsBoolTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertIsBool(null);
    }
}
