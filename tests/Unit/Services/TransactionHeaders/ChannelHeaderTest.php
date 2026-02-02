<?php

declare(strict_types=1);

namespace Tests\Unit\Services\TransactionHeaders;

use Buckaroo\Config\DefaultConfig;
use Buckaroo\Services\TransactionHeaders\ChannelHeader;
use Buckaroo\Services\TransactionHeaders\DefaultHeader;
use Tests\TestCase;

class ChannelHeaderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Clear environment variables that could interfere with tests
        unset($_ENV['Channel']);
    }

    protected function tearDown(): void
    {
        unset($_ENV['Channel']);
        parent::tearDown();
    }

    public function test_appends_channel_header_to_existing_headers(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');
        $baseHeader = new DefaultHeader(['Content-Type: application/json']);

        $channelHeader = new ChannelHeader($baseHeader, $config);
        $headers = $channelHeader->getHeaders();

        $this->assertCount(2, $headers);
        $this->assertSame('Content-Type: application/json', $headers[0]);
        $this->assertStringStartsWith('Channel: ', $headers[1]);
    }

    public function test_uses_default_channel_web(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');
        $baseHeader = new DefaultHeader();

        $channelHeader = new ChannelHeader($baseHeader, $config);
        $headers = $channelHeader->getHeaders();

        $this->assertCount(1, $headers);
        $this->assertSame('Channel: Web', $headers[0]);
    }

    public function test_uses_custom_channel_from_config(): void
    {
        $config = new DefaultConfig(
            'websiteKey',
            'secretKey',
            'test',
            'EUR',
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            'Mobile'
        );
        $baseHeader = new DefaultHeader();

        $channelHeader = new ChannelHeader($baseHeader, $config);
        $headers = $channelHeader->getHeaders();

        $this->assertSame('Channel: Mobile', $headers[0]);
    }

    public function test_uses_merged_channel_value(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');
        $config->merge(['channel' => 'API']);
        $baseHeader = new DefaultHeader();

        $channelHeader = new ChannelHeader($baseHeader, $config);
        $headers = $channelHeader->getHeaders();

        $this->assertSame('Channel: API', $headers[0]);
    }

    public function test_preserves_base_headers_from_chain(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');
        $baseHeader = new DefaultHeader([
            'Authorization: Bearer token',
            'X-Custom-Header: value',
        ]);

        $channelHeader = new ChannelHeader($baseHeader, $config);
        $headers = $channelHeader->getHeaders();

        $this->assertCount(3, $headers);
        $this->assertSame('Authorization: Bearer token', $headers[0]);
        $this->assertSame('X-Custom-Header: value', $headers[1]);
        $this->assertSame('Channel: Web', $headers[2]);
    }

    public function test_channel_header_with_empty_base_headers(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');
        $baseHeader = new DefaultHeader([]);

        $channelHeader = new ChannelHeader($baseHeader, $config);
        $headers = $channelHeader->getHeaders();

        $this->assertCount(1, $headers);
        $this->assertSame('Channel: Web', $headers[0]);
    }
}
