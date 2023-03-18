<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ObjectHasPropertyTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertObjectHasProperty('propertyName', new stdClass);
    }
}
