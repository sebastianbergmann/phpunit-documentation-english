<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class EmptyTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertEmpty(['foo']);
    }
}
