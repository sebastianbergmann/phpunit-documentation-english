<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class EqualsWithArraysCanonicalizingTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertEqualsCanonicalizing([3, 2, 1], [2, 3, 0, 1]);
    }
}
