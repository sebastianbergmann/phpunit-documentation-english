<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class MockForIntersectionExampleTest extends TestCase
{
    public function testCreateMockForIntersection(): void
    {
        $o = $this->createMockForIntersectionOfInterfaces([X::class, Y::class]);

        // $o is of type X ...
        $this->assertInstanceOf(X::class, $o);

        // ... and $o is of type Y
        $this->assertInstanceOf(Y::class, $o);
    }
}
