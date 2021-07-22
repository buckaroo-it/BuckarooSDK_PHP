<?php

declare(strict_types=1);

namespace Buckaroo\SDK\Helpers;

use Buckaroo\SDK\Client;

class Validate
{
    public static function isWebsiteKey($value)
    {
        return mb_strlen($value) == 10;
    }

    public static function isSecretKey($value)
    {
        return mb_strlen($value) >= 5 && mb_strlen($value) <= 50;
    }

    public static function isMode($value)
    {
        return in_array($value, [Client::MODE_LIVE, Client::MODE_TEST]);
    }
}
