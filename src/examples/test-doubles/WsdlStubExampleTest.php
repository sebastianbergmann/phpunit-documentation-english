<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class WsdlStubExampleTest extends TestCase
{
    public function testWebserviceCanBeStubbed(): void
    {
        $service = $this->getMockFromWsdl(__DIR__ . '/HelloService.wsdl');

        $service->method('sayHello')
                ->willReturn('Hello');

        $this->assertSame('Hello', $service->sayHello('message'));
    }
}
