<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ThrowExceptionExampleTest extends TestCase
{
    public function testThrowExceptionStub(): void
    {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(SomeClass::class);

        // Configure the stub.
        $stub->method('doSomething')
            ->willThrowException(new Exception);

        // $stub->doSomething() throws Exception
        $stub->doSomething();
    }
}
