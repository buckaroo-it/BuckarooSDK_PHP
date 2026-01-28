<?php

declare(strict_types=1);

namespace Tests\Unit\Transaction\Request\HttpClient;

use Buckaroo\Config\DefaultConfig;
use Buckaroo\Transaction\Request\HttpClient\GuzzleHttpClientV7;
use Buckaroo\Transaction\Request\HttpClient\HttpClientFactory;
use Buckaroo\Transaction\Request\HttpClient\HttpClientInterface;
use PHPUnit\Framework\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class HttpClientFactoryTest extends TestCase
{
    public function test_creates_http_client_instance(): void
    {
        $config = new DefaultConfig('test-key', 'test-secret', 'test');

        $client = HttpClientFactory::createClient($config);

        $this->assertInstanceOf(HttpClientInterface::class, $client);
    }

    public function test_creates_guzzle_v7_client_for_modern_guzzle(): void
    {
        $config = new DefaultConfig('test-key', 'test-secret', 'test');

        $client = HttpClientFactory::createClient($config);

        // Modern Guzzle (v6+) should create GuzzleHttpClientV7
        $this->assertInstanceOf(GuzzleHttpClientV7::class, $client);
    }

    public function test_factory_method_is_static(): void
    {
        $reflection = new \ReflectionMethod(HttpClientFactory::class, 'createClient');

        $this->assertTrue($reflection->isStatic());
        $this->assertTrue($reflection->isPublic());
    }

    public function test_factory_accepts_config_parameter(): void
    {
        $reflection = new \ReflectionMethod(HttpClientFactory::class, 'createClient');
        $parameters = $reflection->getParameters();

        $this->assertCount(1, $parameters);
        $this->assertSame('config', $parameters[0]->getName());
    }
}
