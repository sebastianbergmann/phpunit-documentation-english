<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ServiceTest extends TestCase
{
    public function testDoSomethingReturnsTrueWhenQueryReturnsRows(): void
    {
        $database = $this->createStub(Database::class);

        $database
            ->method('query')
            ->willReturn([['foo' => 'bar']]);

        $service = new Service($database);

        $this->assertTrue($service->doSomething());
    }
}
