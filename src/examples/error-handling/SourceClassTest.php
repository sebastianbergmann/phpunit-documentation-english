<?php declare(strict_types=1);
namespace example;

use PHPUnit\Framework\TestCase;

final class SourceClassTest extends TestCase
{
    public function testSomething(): void
    {
        (new SourceClass)->doSomething();
        (new SourceClass)->doSomething();

        $this->assertTrue(true);
    }
}
