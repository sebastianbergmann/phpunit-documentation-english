<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ReturnSelfExampleTest extends TestCase
{
    public function testReturnSelf(): void
    {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(SomeClass::class);

        // Configure the stub.
        $stub->method('doSomething')
             ->willReturnSelf();

        // $stub->doSomething() returns $stub
        $this->assertSame($stub, $stub->doSomething());
    }
}
