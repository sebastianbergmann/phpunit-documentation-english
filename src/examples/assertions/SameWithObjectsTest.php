<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class SameWithObjectsTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertSame(new stdClass, new stdClass);
    }
}
