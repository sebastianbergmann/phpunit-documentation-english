<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class IsListTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertIsList([1 => 'foo', '3' => 'bar']);
    }
}
