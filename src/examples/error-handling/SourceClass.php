<?php declare(strict_types=1);
namespace example;

use vendor\VendorClass;

final class SourceClass
{
    public function doSomething(): void
    {
        trigger_error('deprecation', E_USER_DEPRECATED);

        (new VendorClass)->doSomething();
    }
}
