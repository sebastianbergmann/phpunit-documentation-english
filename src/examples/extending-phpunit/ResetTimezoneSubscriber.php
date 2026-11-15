<?php declare(strict_types=1);
namespace Vendor\ExampleExtensionForPhpunit;

use PHPUnit\Event\Test\PreparationStarted;
use PHPUnit\Event\Test\PreparationStartedSubscriber;

final class ResetTimezoneSubscriber implements PreparationStartedSubscriber
{
    public function notify(PreparationStarted $event): void
    {
        date_default_timezone_set('UTC');
    }
}
