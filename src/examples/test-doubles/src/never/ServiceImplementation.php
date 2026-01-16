<?php declare(strict_types=1);
final readonly class ServiceImplementation implements Service
{
    private ErrorHandler $errorHandler;

    public function __construct(ErrorHandler $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }

    public function doSomething(): void
    {
        try {
            // ...

            throw new Exception('message');
        } catch (Exception $e) {
            $this->errorHandler->handle($e);
        }
    }
}
