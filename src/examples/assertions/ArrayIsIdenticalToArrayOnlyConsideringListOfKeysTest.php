<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ArrayIsIdenticalToArrayOnlyConsideringListOfKeysTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertArrayIsIdenticalToArrayOnlyConsideringListOfKeys(
            [
                'timestamp' => time(),
                'foo'       => 'bar',
            ],
            [
                'timestamp' => time(),
                'foo'       => 'baz',
            ],
            [
                'foo',
            ]
        );
    }
}
