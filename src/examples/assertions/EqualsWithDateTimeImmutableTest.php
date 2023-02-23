<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class EqualsWithDateTimeImmutableTest extends TestCase
{
    public function testFailure(): void
    {
        $expected = new DateTimeImmutable('2023-02-23 01:23:45');
        $actual   = new DateTimeImmutable('2023-02-23 01:23:46');

        $this->assertEquals($expected, $actual);
    }
}
