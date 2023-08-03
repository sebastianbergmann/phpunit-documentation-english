<?php declare(strict_types=1);
namespace Vendor\ExampleExtensionForPhpunit;

use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;

final class ExampleExtension implements Extension
{
    public function bootstrap(
        Configuration $configuration,
        Facade $facade,
        ParameterCollection $parameters
    ): void {
        if ($configuration->noOutput()) {
            return;
        }

        $message = 'the-default-message';

        if ($parameters->has('message')) {
            $message = $parameters->get('message');
        }

        $facade->registerSubscriber(new ExampleSubscriber($message));
        $facade->registerTracer(new ExampleTracer);
    }
}
