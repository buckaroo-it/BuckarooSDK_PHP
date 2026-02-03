<?php

declare(strict_types=1);

namespace Tests\Unit\Services\TransactionHeaders;

use Buckaroo\Config\DefaultConfig;
use Buckaroo\Services\TransactionHeaders\CultureHeader;
use Buckaroo\Services\TransactionHeaders\DefaultHeader;
use Tests\TestCase;

class CultureHeaderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Clear environment variables that could interfere with tests
        unset($_ENV['Culture']);
    }

    protected function tearDown(): void
    {
        unset($_ENV['Culture']);
        parent::tearDown();
    }

    public function test_appends_culture_header_to_existing_headers(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');
        $baseHeader = new DefaultHeader(['Content-Type: application/json']);

        $cultureHeader = new CultureHeader($baseHeader, $config);
        $headers = $cultureHeader->getHeaders();

        $this->assertCount(2, $headers);
        $this->assertSame('Content-Type: application/json', $headers[0]);
        $this->assertStringStartsWith('Culture: ', $headers[1]);
    }

    public function test_uses_default_culture_en_gb(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');
        $baseHeader = new DefaultHeader();

        $cultureHeader = new CultureHeader($baseHeader, $config);
        $headers = $cultureHeader->getHeaders();

        $this->assertCount(1, $headers);
        $this->assertSame('Culture: en-GB', $headers[0]);
    }

    public function test_uses_custom_culture_from_config(): void
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
            'nl-NL'
        );
        $baseHeader = new DefaultHeader();

        $cultureHeader = new CultureHeader($baseHeader, $config);
        $headers = $cultureHeader->getHeaders();

        $this->assertSame('Culture: nl-NL', $headers[0]);
    }

    public function test_uses_merged_culture_value(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');
        $config->merge(['culture' => 'de-DE']);
        $baseHeader = new DefaultHeader();

        $cultureHeader = new CultureHeader($baseHeader, $config);
        $headers = $cultureHeader->getHeaders();

        $this->assertSame('Culture: de-DE', $headers[0]);
    }

    public function test_preserves_base_headers_from_chain(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');
        $baseHeader = new DefaultHeader([
            'Authorization: Bearer token',
            'X-Custom-Header: value',
        ]);

        $cultureHeader = new CultureHeader($baseHeader, $config);
        $headers = $cultureHeader->getHeaders();

        $this->assertCount(3, $headers);
        $this->assertSame('Authorization: Bearer token', $headers[0]);
        $this->assertSame('X-Custom-Header: value', $headers[1]);
        $this->assertSame('Culture: en-GB', $headers[2]);
    }

    public function test_culture_header_with_empty_base_headers(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');
        $baseHeader = new DefaultHeader([]);

        $cultureHeader = new CultureHeader($baseHeader, $config);
        $headers = $cultureHeader->getHeaders();

        $this->assertCount(1, $headers);
        $this->assertSame('Culture: en-GB', $headers[0]);
    }
}
