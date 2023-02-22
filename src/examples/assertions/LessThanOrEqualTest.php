<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class LessThanOrEqualTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertLessThanOrEqual(1, 2);
    }
}
