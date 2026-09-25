<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase
{
    public function testSomething(): void
    {
        // ...

        $this->provideAdditionalInformation(
            json_encode(['key' => 'value'], JSON_THROW_ON_ERROR),
        );

        // ...
    }
}
