<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ReturnCallbackExampleTest extends TestCase
{
    public function testReturnCallbackStub(): void
    {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(SomeClass::class);

        // Configure the stub.
        $stub->method('doSomething')
            ->willReturnCallback('str_rot13');

        // $stub->doSomething($argument) returns str_rot13($argument)
        $this->assertSame('fbzrguvat', $stub->doSomething('something'));
    }
}
