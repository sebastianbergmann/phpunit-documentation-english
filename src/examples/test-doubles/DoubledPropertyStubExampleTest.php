<?php declare(strict_types=1);
use PHPUnit\Framework\MockObject\Runtime\PropertyHook;
use PHPUnit\Framework\TestCase;

final class DoubledPropertyStubExampleTest extends TestCase
{
    public function testExample(): void
    {
        $stub = $this->getStubBuilder(ClassWithPropertyWithoutHooks::class)
            ->doubleProperties(['property'])
            ->getStub();

        $stub
            ->method(PropertyHook::get('property'))
            ->willReturn('value');

        $this->assertSame('value', $stub->property);
    }
}
