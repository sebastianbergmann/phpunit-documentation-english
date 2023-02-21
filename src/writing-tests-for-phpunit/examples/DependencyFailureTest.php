<?php declare(strict_types=1);
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

final class DependencyFailureTest extends TestCase
{
    public function testOne(): void
    {
        $this->assertTrue(false);
    }

    #[Depends('testOne')]
    public function testTwo(): void
    {
    }
}
