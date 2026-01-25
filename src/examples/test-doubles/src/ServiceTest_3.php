<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ServiceTest extends TestCase
{
    public function testDoSomethingElseExecutesInsertQuery(): void
    {
        $database = $this->createMock(Database::class);

        $database
            ->expects($this->once())
            ->method('execute')
            ->with(
                'INSERT INTO bar (foo, baz) VALUES (?, ?);',
                'value',
                'another value',
            );

        $service = new Service($database);

        $service->doSomethingElse();
    }
}
