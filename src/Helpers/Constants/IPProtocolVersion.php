<?php declare (strict_types = 1);

namespace Buckaroo\SDK\Helpers\Constants;

class IPProtocolVersion
{
    const IPv4 = 0;

    const IPv6 = 1;

    /**
     * Get the value of the ipaddress version (IPv4 or IPv6)
     *
     * @param  string $ipAddress
     * @return int
     */
    public static function getVersion($ipAddress = '0.0.0.0')
    {
        return filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ? static::IPv6 : static::IPv4;
    }
}
