<?php declare(strict_types=1);
namespace Vendor\ExampleExtensionForPhpunit;

use PHPUnit\Event\Event;
use PHPUnit\Event\Tracer\Tracer;

final class ExampleTracer implements Tracer
{
    public function trace(Event $event): void
    {
        // ...
    }
}
