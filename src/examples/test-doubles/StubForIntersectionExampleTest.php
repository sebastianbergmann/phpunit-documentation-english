<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class StubForIntersectionExampleTest extends TestCase
{
    public function testCreateStubForIntersection(): void
    {
        $o = $this->createStubForIntersectionOfInterfaces([X::class, Y::class]);

        // $o is of type X ...
        $this->assertInstanceOf(X::class, $o);

        // ... and $o is of type Y
        $this->assertInstanceOf(Y::class, $o);
    }
}
