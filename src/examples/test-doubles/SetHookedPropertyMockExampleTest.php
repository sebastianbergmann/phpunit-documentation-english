<?php declare(strict_types=1);
use PHPUnit\Framework\MockObject\Runtime\PropertyHook;
use PHPUnit\Framework\TestCase;

final class SetHookedPropertyMockExampleTest extends TestCase
{
    public function testExample(): void
    {
        $mock = $this->createMock(
            InterfaceWithSetHookedProperty::class,
        );

        $mock
            ->expects($this->once())
            ->method(PropertyHook::set('property'))
            ->with('value');

        $mock->property = 'value';
    }
}
