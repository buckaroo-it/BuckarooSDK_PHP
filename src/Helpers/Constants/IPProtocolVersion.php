<?php

declare(strict_types=1);

namespace Buckaroo\Helpers\Constants;

class IPProtocolVersion
{
    public const IPV4 = 0;
    public const IPV6 = 1;

    public static function getVersion(string $ipAddress = '0.0.0.0'): int
    {
        return filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ? static::IPV6 : static::IPV4;
    }
}
