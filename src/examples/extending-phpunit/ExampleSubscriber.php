<?php declare(strict_types=1);
namespace Vendor\ExampleExtensionForPhpunit;

use PHPUnit\Event\TestRunner\ExecutionFinished;
use PHPUnit\Event\TestRunner\ExecutionFinishedSubscriber;

final class ExampleSubscriber implements ExecutionFinishedSubscriber
{
    public function __construct(private readonly string $message)
    {
    }

    public function notify(ExecutionFinished $event): void
    {
        print __METHOD__ . PHP_EOL . $this->message . PHP_EOL;
    }
}
