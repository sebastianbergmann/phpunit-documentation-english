<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ContainsOnlyCallableTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertContainsOnlyCallable([null]);
    }
}
