<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ContainsOnlyBoolTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertContainsOnlyBool([null]);
    }
}
