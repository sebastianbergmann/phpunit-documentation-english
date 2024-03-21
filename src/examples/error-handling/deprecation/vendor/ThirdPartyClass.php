<?php declare(strict_types=1);
namespace vendor;

use example\FirstPartyClass;

final class ThirdPartyClass
{
    public function method(): void
    {
        trigger_error('deprecation in third-party code', E_USER_DEPRECATED);
    }

    public function anotherMethod(): true
    {
        return (new FirstPartyClass)->method();
    }
}
