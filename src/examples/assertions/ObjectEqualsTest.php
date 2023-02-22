<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ObjectEqualsTest extends TestCase
{
    public function testSomething(): void
    {
        $a = new Email('user@example.org');
        $b = new Email('user@example.org');
        $c = new Email('user@example.com');

        // This passes
        $this->assertObjectEquals($a, $b);

        // This fails
        $this->assertObjectEquals($a, $c);
    }
}
