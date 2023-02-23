<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class SomeClassTest extends TestCase
{
    public function testDoesSomething(): void
    {
        $sut = new SomeClass;

        // Create a test stub for the Dependency interface
        $dependency = $this->createStub(Dependency::class);

        // Configure the test stub
        $dependency->method('doSomething')
            ->willReturn('foo');

        $result = $sut->doSomething($dependency);

        $this->assertStringEndsWith('foo', $result);
    }
}
