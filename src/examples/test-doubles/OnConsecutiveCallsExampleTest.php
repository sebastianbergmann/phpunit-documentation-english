<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class OnConsecutiveCallsExampleTest extends TestCase
{
    public function testOnConsecutiveCallsStub(): void
    {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(SomeClass::class);

        // Configure the stub.
        $stub->method('doSomething')
             ->willReturn(1, 2, 3);

        // $stub->doSomething() returns a different value each time
        $this->assertSame(1, $stub->doSomething());
        $this->assertSame(2, $stub->doSomething());
        $this->assertSame(3, $stub->doSomething());
    }
}
