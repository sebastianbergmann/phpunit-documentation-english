<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class AbstractTraitTest extends TestCase
{
    public function testConcreteMethod(): void
    {
        $mock = $this->getMockForTrait(AbstractTrait::class);

        $mock->expects($this->any())
             ->method('abstractMethod')
             ->willReturn(true);

        $this->assertTrue($mock->concreteMethod());
    }
}
