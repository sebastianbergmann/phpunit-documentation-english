<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class NanTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertNan(1);
    }
}
