<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ContainsOnlyResourceTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertContainsOnlyResource([null]);
    }
}
