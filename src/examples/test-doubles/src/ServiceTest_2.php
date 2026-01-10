<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ServiceTest extends TestCase
{
    public function testExceptionIsThrownWhenSomethingGoesWrong(): void
    {
        $database = $this->createStub(Database::class);

        $database
            ->method('query')
            ->willThrowException(new DatabaseException);

        $service = new Service($database);

        $this->expectException(ServiceException::class);

        $service->doSomething();
    }
}
