<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ReturnMapExampleTest extends TestCase
{
    public function testReturnMapStub(): void
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
            ->willReturnMap($map);

        // $stub->doSomething() returns different values depending on
        // the provided arguments.
        $this->assertSame('d', $stub->doSomething('a', 'b', 'c'));
        $this->assertSame('h', $stub->doSomething('e', 'f', 'g'));
    }
}
