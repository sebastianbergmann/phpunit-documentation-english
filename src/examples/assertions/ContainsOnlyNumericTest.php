<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ContainsOnlyNumericTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertContainsOnlyNumeric([null]);
    }
}
