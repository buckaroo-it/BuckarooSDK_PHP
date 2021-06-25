<?php

declare(strict_types=1);

namespace Buckaroo\SDK\Helpers\Constants;

class IPProtocolVersion
{
    public const IPV4 = 0;
    public const IPV6 = 1;

    /**
     * Get the value of the ipaddress version (IPV4 or IPV6)
     *
     * @param  string $ipAddress
     * @return int
     */
    public static function getVersion($ipAddress = '0.0.0.0')
    {
        return filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ? static::IPV6 : static::IPV4;
    }
}
