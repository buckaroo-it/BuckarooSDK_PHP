<?php

declare(strict_types=1);

namespace Buckaroo\Helpers;

use Buckaroo\Client;

class Validate
{
    public static function isWebsiteKey(string $websiteKey): bool
    {
        return mb_strlen($websiteKey) == 10;
    }

    public static function isSecretKey(string $secretKey): bool
    {
        return mb_strlen($secretKey) >= 5 && mb_strlen($secretKey) <= 50;
    }

    public static function isMode(string $mode): bool
    {
        return in_array($mode, [Client::MODE_LIVE, Client::MODE_TEST]);
    }

    public static function isCurrency(string $currency): bool
    {
        return ($currency && (strlen($currency) == 3));
    }

    public static function isIp(string $ip): bool
    {
        return
            (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false)
            || (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false);
    }

    public static function isServiceName(string $service): bool
    {
        return (!empty($service) && Base::getMethods()[$service]);
    }

    public static function isServiceAction(string $action): bool
    {
        return !empty($action);
    }

    public static function isServiceVersion(int $version): bool
    {
        return in_array($version, [0,1,2,3]);
    }
}
