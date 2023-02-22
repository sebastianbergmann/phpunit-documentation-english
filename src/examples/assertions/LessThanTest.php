<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class LessThanTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertLessThan(1, 2);
    }
}
