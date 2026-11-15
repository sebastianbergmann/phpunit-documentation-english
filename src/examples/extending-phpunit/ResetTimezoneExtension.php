<?php declare(strict_types=1);
namespace Vendor\ExampleExtensionForPhpunit;

use PHPUnit\Runner\Extension\ChildProcessExtension;
use PHPUnit\Runner\Extension\ChildProcessFacade;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;

final class ResetTimezoneExtension implements ChildProcessExtension
{
    public function bootstrap(
        Configuration $configuration,
        Facade $facade,
        ParameterCollection $parameters
    ): void {
        // Called in the main process
        $facade->registerSubscriberForEventsOfThisProcess(
            new ResetTimezoneSubscriber,
        );
    }

    public function bootstrapChildProcess(
        Configuration $configuration,
        ChildProcessFacade $facade,
        ParameterCollection $parameters
    ): void {
        // Called in a child process before it runs its first test
        $facade->registerSubscriber(
            new ResetTimezoneSubscriber,
        );
    }

    public function shutdownChildProcess(): void
    {
        // Called in a child process after it has run its last test
    }
}
