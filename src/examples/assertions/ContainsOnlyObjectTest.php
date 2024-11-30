<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ContainsOnlyObjectTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertContainsOnlyObject([null]);
    }
}
