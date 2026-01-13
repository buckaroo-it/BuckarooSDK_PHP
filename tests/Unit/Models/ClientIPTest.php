<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use Buckaroo\Models\ClientIP;
use Buckaroo\Resources\Constants\IPProtocolVersion;
use Tests\TestCase;

class ClientIPTest extends TestCase
{
    public function test_constructor_with_explicit_ip_and_type(): void
    {
        $clientIP = new ClientIP('192.168.1.100', IPProtocolVersion::IPV4);

        $this->assertSame('192.168.1.100', $clientIP->Address);
        $this->assertSame(IPProtocolVersion::IPV4, $clientIP->Type);
    }

    public function test_constructor_with_ipv4_auto_detects_type(): void
    {
        $clientIP = new ClientIP('10.0.0.1');

        $this->assertSame('10.0.0.1', $clientIP->Address);
        $this->assertSame(IPProtocolVersion::IPV4, $clientIP->Type);
    }

    public function test_constructor_with_ipv6_auto_detects_type(): void
    {
        $clientIP = new ClientIP('2001:0db8:85a3:0000:0000:8a2e:0370:7334');

        $this->assertSame('2001:0db8:85a3:0000:0000:8a2e:0370:7334', $clientIP->Address);
        $this->assertSame(IPProtocolVersion::IPV6, $clientIP->Type);
    }

    public function test_constructor_without_parameters_defaults_to_localhost(): void
    {
        $originalServer = $_SERVER;

        try
        {
            $_SERVER = [];
            $clientIP = new ClientIP();

            $this->assertSame('127.0.0.1', $clientIP->Address);
            $this->assertSame(IPProtocolVersion::IPV4, $clientIP->Type);
        } finally
        {
            $_SERVER = $originalServer;
        }
    }

    public function test_auto_detection_uses_remote_addr_from_server(): void
    {
        $originalServer = $_SERVER;
        $_SERVER['REMOTE_ADDR'] = '203.0.113.45';

        $clientIP = new ClientIP();

        $this->assertSame('203.0.113.45', $clientIP->Address);
        $this->assertSame(IPProtocolVersion::IPV4, $clientIP->Type);

        $_SERVER = $originalServer;
    }

    public function test_auto_detection_uses_http_x_forwarded_for_from_server(): void
    {
        $originalServer = $_SERVER;
        $_SERVER['HTTP_X_FORWARDED_FOR'] = '198.51.100.23';
        $_SERVER['REMOTE_ADDR'] = '10.0.0.1';

        $clientIP = new ClientIP();

        $this->assertSame('198.51.100.23', $clientIP->Address);
        $this->assertSame(IPProtocolVersion::IPV4, $clientIP->Type);

        $_SERVER = $originalServer;
    }

    public function test_auto_detection_skips_invalid_ip_in_http_x_forwarded_for(): void
    {
        $originalServer = $_SERVER;
        $_SERVER['HTTP_X_FORWARDED_FOR'] = 'not-an-ip';
        $_SERVER['REMOTE_ADDR'] = '192.168.1.50';

        $clientIP = new ClientIP();

        $this->assertSame('192.168.1.50', $clientIP->Address);
        $this->assertSame(IPProtocolVersion::IPV4, $clientIP->Type);

        $_SERVER = $originalServer;
    }

    public function test_auto_detection_skips_invalid_remote_addr_and_defaults(): void
    {
        $originalServer = $_SERVER;
        $_SERVER['REMOTE_ADDR'] = 'invalid-ip-address';

        $clientIP = new ClientIP();

        $this->assertSame('127.0.0.1', $clientIP->Address);
        $this->assertSame(IPProtocolVersion::IPV4, $clientIP->Type);

        $_SERVER = $originalServer;
    }

    public function test_ipv6_address_gets_correct_type(): void
    {
        $clientIP = new ClientIP('::1');

        $this->assertSame('::1', $clientIP->Address);
        $this->assertSame(IPProtocolVersion::IPV6, $clientIP->Type);
    }

    public function test_ipv6_from_remote_addr_auto_detects_type(): void
    {
        $originalServer = $_SERVER;
        $_SERVER['REMOTE_ADDR'] = '2001:db8::1';

        $clientIP = new ClientIP();

        $this->assertSame('2001:db8::1', $clientIP->Address);
        $this->assertSame(IPProtocolVersion::IPV6, $clientIP->Type);

        $_SERVER = $originalServer;
    }

    public function test_to_array_returns_type_and_address(): void
    {
        $clientIP = new ClientIP('172.16.0.1', IPProtocolVersion::IPV4);

        $array = $clientIP->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('Type', $array);
        $this->assertArrayHasKey('Address', $array);
        $this->assertSame(IPProtocolVersion::IPV4, $array['Type']);
        $this->assertSame('172.16.0.1', $array['Address']);
    }

    public function test_to_array_with_ipv6(): void
    {
        $clientIP = new ClientIP('fe80::1', IPProtocolVersion::IPV6);

        $array = $clientIP->toArray();

        $this->assertSame(IPProtocolVersion::IPV6, $array['Type']);
        $this->assertSame('fe80::1', $array['Address']);
    }

    public function test_null_ip_with_explicit_type_uses_auto_detection_for_ip(): void
    {
        $originalServer = $_SERVER;
        $_SERVER['REMOTE_ADDR'] = '10.20.30.40';

        $clientIP = new ClientIP(null, IPProtocolVersion::IPV6);

        $this->assertSame('10.20.30.40', $clientIP->Address);
        $this->assertSame(IPProtocolVersion::IPV6, $clientIP->Type);

        $_SERVER = $originalServer;
    }

    public function test_explicit_type_overrides_auto_detection(): void
    {
        $clientIP = new ClientIP('192.168.1.1', IPProtocolVersion::IPV6);

        $this->assertSame('192.168.1.1', $clientIP->Address);
        $this->assertSame(IPProtocolVersion::IPV6, $clientIP->Type);
    }

    public function test_property_access_via_magic_get(): void
    {
        $clientIP = new ClientIP('8.8.8.8');

        $this->assertSame('8.8.8.8', $clientIP->Address);
        $this->assertSame(IPProtocolVersion::IPV4, $clientIP->Type);
        $this->assertNull($clientIP->nonExistentProperty);
    }
}
