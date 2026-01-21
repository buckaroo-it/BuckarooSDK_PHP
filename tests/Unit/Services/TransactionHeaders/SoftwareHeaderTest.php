<?php

declare(strict_types=1);

namespace Tests\Unit\Services\TransactionHeaders;

use Buckaroo\Config\DefaultConfig;
use Buckaroo\Services\TransactionHeaders\DefaultHeader;
use Buckaroo\Services\TransactionHeaders\SoftwareHeader;
use Tests\TestCase;

class SoftwareHeaderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Clear environment variables that could interfere with tests
        unset(
            $_ENV['PlatformName'],
            $_ENV['PlatformVersion'],
            $_ENV['ModuleSupplier'],
            $_ENV['ModuleName'],
            $_ENV['ModuleVersion']
        );
    }

    protected function tearDown(): void
    {
        unset(
            $_ENV['PlatformName'],
            $_ENV['PlatformVersion'],
            $_ENV['ModuleSupplier'],
            $_ENV['ModuleName'],
            $_ENV['ModuleVersion']
        );
        parent::tearDown();
    }

    public function test_appends_software_header_to_existing_headers(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');
        $baseHeader = new DefaultHeader(['Content-Type: application/json']);

        $softwareHeader = new SoftwareHeader($baseHeader, $config);
        $headers = $softwareHeader->getHeaders();

        $this->assertCount(2, $headers);
        $this->assertSame('Content-Type: application/json', $headers[0]);
        $this->assertStringStartsWith('Software: ', $headers[1]);
    }

    public function test_software_header_contains_platform_info(): void
    {
        $config = new DefaultConfig(
            'websiteKey',
            'secretKey',
            'test',
            'EUR',
            null,
            null,
            null,
            'MyPlatform',
            '2.0.0',
            'MySupplier',
            'MyModule',
            '1.5.0'
        );
        $baseHeader = new DefaultHeader();

        $softwareHeader = new SoftwareHeader($baseHeader, $config);
        $headers = $softwareHeader->getHeaders();

        $this->assertCount(1, $headers);

        $softwareJson = str_replace('Software: ', '', $headers[0]);
        $softwareData = json_decode($softwareJson, true);

        $this->assertSame('MyPlatform', $softwareData['PlatformName']);
        $this->assertSame('2.0.0', $softwareData['PlatformVersion']);
        $this->assertSame('MySupplier', $softwareData['ModuleSupplier']);
        $this->assertSame('MyModule', $softwareData['ModuleName']);
        $this->assertSame('1.5.0', $softwareData['ModuleVersion']);
    }

    public function test_software_header_uses_default_platform_values(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');
        $baseHeader = new DefaultHeader();

        $softwareHeader = new SoftwareHeader($baseHeader, $config);
        $headers = $softwareHeader->getHeaders();

        $softwareJson = str_replace('Software: ', '', $headers[0]);
        $softwareData = json_decode($softwareJson, true);

        $this->assertSame('Empty Platform Name', $softwareData['PlatformName']);
        $this->assertSame('1.0.0', $softwareData['PlatformVersion']);
        $this->assertSame('Empty Module Supplier', $softwareData['ModuleSupplier']);
        $this->assertSame('Empty Module name', $softwareData['ModuleName']);
        $this->assertSame('1.0.0', $softwareData['ModuleVersion']);
    }

    public function test_software_header_produces_valid_json(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');
        $baseHeader = new DefaultHeader();

        $softwareHeader = new SoftwareHeader($baseHeader, $config);
        $headers = $softwareHeader->getHeaders();

        $softwareJson = str_replace('Software: ', '', $headers[0]);
        $decoded = json_decode($softwareJson, true);

        $this->assertIsArray($decoded);
        $this->assertArrayHasKey('PlatformName', $decoded);
        $this->assertArrayHasKey('PlatformVersion', $decoded);
        $this->assertArrayHasKey('ModuleSupplier', $decoded);
        $this->assertArrayHasKey('ModuleName', $decoded);
        $this->assertArrayHasKey('ModuleVersion', $decoded);
    }

    public function test_preserves_base_headers_from_chain(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');
        $baseHeader = new DefaultHeader([
            'Authorization: Bearer token',
            'X-Custom-Header: value',
        ]);

        $softwareHeader = new SoftwareHeader($baseHeader, $config);
        $headers = $softwareHeader->getHeaders();

        $this->assertCount(3, $headers);
        $this->assertSame('Authorization: Bearer token', $headers[0]);
        $this->assertSame('X-Custom-Header: value', $headers[1]);
        $this->assertStringStartsWith('Software: ', $headers[2]);
    }
}
