<?php declare(strict_types=1);
namespace example;

use function trigger_error;
use vendor\ThirdPartyClass;

final class FirstPartyClass
{
    public function method(): true
    {
        (new ThirdPartyClass)->method();

        trigger_error('deprecation in first-party code', E_USER_DEPRECATED);

        return true;
    }
}
