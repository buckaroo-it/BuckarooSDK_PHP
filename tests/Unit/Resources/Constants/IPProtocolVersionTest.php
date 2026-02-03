<?php

declare(strict_types=1);

namespace Tests\Unit\Resources\Constants;

use Buckaroo\Resources\Constants\IPProtocolVersion;
use PHPUnit\Framework\TestCase;

class IPProtocolVersionTest extends TestCase
{
    public function test_ipv4_constant(): void
    {
        $this->assertSame(0, IPProtocolVersion::IPV4);
    }

    public function test_ipv6_constant(): void
    {
        $this->assertSame(1, IPProtocolVersion::IPV6);
    }

    public function test_constants_are_integer_type(): void
    {
        $this->assertIsInt(IPProtocolVersion::IPV4);
        $this->assertIsInt(IPProtocolVersion::IPV6);
    }

    public function test_get_version_returns_ipv4_for_ipv4_address(): void
    {
        $this->assertSame(
            IPProtocolVersion::IPV4,
            IPProtocolVersion::getVersion('192.168.1.1')
        );
    }

    public function test_get_version_returns_ipv4_for_localhost(): void
    {
        $this->assertSame(
            IPProtocolVersion::IPV4,
            IPProtocolVersion::getVersion('127.0.0.1')
        );
    }

    public function test_get_version_returns_ipv6_for_ipv6_address(): void
    {
        $this->assertSame(
            IPProtocolVersion::IPV6,
            IPProtocolVersion::getVersion('2001:0db8:85a3:0000:0000:8a2e:0370:7334')
        );
    }

    public function test_get_version_returns_ipv6_for_short_ipv6(): void
    {
        $this->assertSame(
            IPProtocolVersion::IPV6,
            IPProtocolVersion::getVersion('::1')
        );
    }

    public function test_get_version_returns_ipv4_for_default(): void
    {
        $this->assertSame(
            IPProtocolVersion::IPV4,
            IPProtocolVersion::getVersion()
        );
    }

    public function test_get_version_returns_ipv4_for_invalid_ip(): void
    {
        // Invalid IP addresses are treated as IPv4 (default behavior)
        $this->assertSame(
            IPProtocolVersion::IPV4,
            IPProtocolVersion::getVersion('not-an-ip')
        );
    }

    public function test_get_version_returns_ipv4_for_empty_string(): void
    {
        $this->assertSame(
            IPProtocolVersion::IPV4,
            IPProtocolVersion::getVersion('')
        );
    }

    /**
     * @dataProvider ipv4AddressesProvider
     */
    public function test_get_version_identifies_ipv4_addresses(string $ip): void
    {
        $this->assertSame(
            IPProtocolVersion::IPV4,
            IPProtocolVersion::getVersion($ip)
        );
    }

    public static function ipv4AddressesProvider(): array
    {
        return [
            'standard' => ['192.168.1.1'],
            'localhost' => ['127.0.0.1'],
            'broadcast' => ['255.255.255.255'],
            'zero' => ['0.0.0.0'],
            'public' => ['8.8.8.8'],
        ];
    }

    /**
     * @dataProvider ipv6AddressesProvider
     */
    public function test_get_version_identifies_ipv6_addresses(string $ip): void
    {
        $this->assertSame(
            IPProtocolVersion::IPV6,
            IPProtocolVersion::getVersion($ip)
        );
    }

    public static function ipv6AddressesProvider(): array
    {
        return [
            'full' => ['2001:0db8:85a3:0000:0000:8a2e:0370:7334'],
            'compressed' => ['2001:db8:85a3::8a2e:370:7334'],
            'localhost' => ['::1'],
            'all_zeros' => ['::'],
            'link_local' => ['fe80::1'],
        ];
    }
}
