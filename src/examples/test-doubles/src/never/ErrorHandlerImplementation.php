<?php declare(strict_types=1);
final readonly class ErrorHandlerImplementation implements ErrorHandler
{
    public function handle(Exception $e): never
    {
        print $e->getMessage();

        exit;
    }
}
