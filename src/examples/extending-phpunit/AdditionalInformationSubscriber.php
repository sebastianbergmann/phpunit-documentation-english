<?php declare(strict_types=1);
namespace Vendor\ExampleExtensionForPhpunit;

use PHPUnit\Event\Test\AdditionalInformationProvided;
use PHPUnit\Event\Test\AdditionalInformationProvidedSubscriber;

final class AdditionalInformationSubscriber implements AdditionalInformationProvidedSubscriber
{
    public function notify(AdditionalInformationProvided $event): void
    {
        $test        = $event->test();
        $information = json_decode(
            $event->additionalInformation(),
            true,
            512,
            JSON_THROW_ON_ERROR,
        );

        // ...
    }
}
