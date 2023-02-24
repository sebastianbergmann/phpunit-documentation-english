<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class CreateConfiguredMockExampleTest extends TestCase
{
    public function testCreateConfiguredMock(): void
    {
        $o = $this->createConfiguredMock(
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
