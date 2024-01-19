<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ArrayIsEqualToArrayIgnoringListOfKeysTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertArrayIsEqualToArrayIgnoringListOfKeys(
            [
                'timestamp' => time(),
                'foo'       => 'bar',
            ],
            [
                'timestamp' => time(),
                'foo'       => 'baz',
            ],
            [
                'timestamp',
            ]
        );
    }
}
