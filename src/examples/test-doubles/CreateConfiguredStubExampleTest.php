<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class CreateConfiguredStubExampleTest extends TestCase
{
    public function testCreateConfiguredStub(): void
    {
        $o = $this->createConfiguredStub(
            SomeInterface::class,
            [
                'doSomething'     => 'foo',
                'doSomethingElse' => 'bar',
            ]
        );

        // $o->doSomething() now returns 'foo'
        $this->assertSame('foo', $o->doSomething());

        // $o->doSomethingElse() now returns 'bar'
        $this->assertSame('bar', $o->doSomethingElse());
    }
}
