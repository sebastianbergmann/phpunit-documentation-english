<?php declare(strict_types=1);
namespace vendor;

final class VendorClass
{
    public function doSomething(): void
    {
        trigger_error('deprecation', E_USER_DEPRECATED);
    }
}
