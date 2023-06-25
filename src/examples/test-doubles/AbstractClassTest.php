<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class AbstractClassTest extends TestCase
{
    public function testConcreteMethod(): void
    {
        $stub = $this->getMockForAbstractClass(AbstractClass::class);

        $stub->expects($this->any())
             ->method('abstractMethod')
             ->willReturn(true);

        $this->assertTrue($stub->concreteMethod());
    }
}
