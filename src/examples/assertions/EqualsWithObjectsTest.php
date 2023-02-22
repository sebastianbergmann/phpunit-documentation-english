<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class EqualsWithObjectsTest extends TestCase
{
    public function testFailure(): void
    {
        $expected      = new stdClass;
        $expected->foo = 'foo';
        $expected->bar = 'bar';

        $actual      = new stdClass;
        $actual->foo = 'bar';
        $actual->baz = 'bar';

        $this->assertEquals($expected, $actual);
    }
}
