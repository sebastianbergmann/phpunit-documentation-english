<?php declare(strict_types=1);
use PHPUnit\Framework\MockObject\Runtime\PropertyHook;
use PHPUnit\Framework\TestCase;

final class GetHookedPropertyStubExampleTest extends TestCase
{
    public function testExample(): void
    {
        $stub = $this->createStub(
            InterfaceWithGetHookedProperty::class,
        );

        $stub
            ->method(PropertyHook::get('property'))
            ->willReturn('value');

        $this->assertSame('value', $stub->property);
    }
}
