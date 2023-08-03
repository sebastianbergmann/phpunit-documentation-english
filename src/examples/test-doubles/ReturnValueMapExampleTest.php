<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ReturnValueMapExampleTest extends TestCase
{
    public function testReturnValueMapStub(): void
    {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(SomeClass::class);

        // Create a map of arguments to return values.
        $map = [
            ['a', 'b', 'c', 'd'],
            ['e', 'f', 'g', 'h'],
        ];

        // Configure the stub.
        $stub->method('doSomething')
            ->willReturnValueMap($map);

        // $stub->doSomething() returns different values depending on
        // the provided arguments.
        $this->assertSame('d', $stub->doSomething('a', 'b', 'c'));
        $this->assertSame('h', $stub->doSomething('e', 'f', 'g'));
    }
}
