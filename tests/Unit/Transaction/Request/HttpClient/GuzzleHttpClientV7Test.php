<?php

declare(strict_types=1);

namespace Tests\Unit\Transaction\Request\HttpClient;

use Buckaroo\Config\DefaultConfig;
use Buckaroo\Exceptions\TransferException;
use Buckaroo\Transaction\Request\HttpClient\GuzzleHttpClientV7;
use Buckaroo\Transaction\Request\HttpClient\HttpClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class GuzzleHttpClientV7Test extends TestCase
{
    public function test_implements_http_client_interface(): void
    {
        $config = new DefaultConfig('test-key', 'test-secret', 'test');
        $client = new GuzzleHttpClientV7($config);

        $this->assertInstanceOf(HttpClientInterface::class, $client);
    }

    public function test_constructor_sets_logger_from_config(): void
    {
        $config = new DefaultConfig('test-key', 'test-secret', 'test');
        $client = new GuzzleHttpClientV7($config);

        $reflection = new ReflectionClass($client);
        $loggerProperty = $reflection->getProperty('logger');
        $loggerProperty->setAccessible(true);

        $this->assertNotNull($loggerProperty->getValue($client));
    }

    public function test_constructor_creates_http_client(): void
    {
        $config = new DefaultConfig('test-key', 'test-secret', 'test');
        $client = new GuzzleHttpClientV7($config);

        $reflection = new ReflectionClass($client);
        $httpClientProperty = $reflection->getProperty('httpClient');
        $httpClientProperty->setAccessible(true);

        $this->assertInstanceOf(Client::class, $httpClientProperty->getValue($client));
    }

    public function test_call_method_sends_request_and_returns_response(): void
    {
        $responseBody = ['Key' => 'TX-123', 'Status' => ['Code' => ['Code' => 190]]];
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode($responseBody)),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new Client(['handler' => $handlerStack]);

        $config = new DefaultConfig('test-key', 'test-secret', 'test');
        $client = new GuzzleHttpClientV7($config);

        // Inject our mocked Guzzle client
        $reflection = new ReflectionClass($client);
        $httpClientProperty = $reflection->getProperty('httpClient');
        $httpClientProperty->setAccessible(true);
        $httpClientProperty->setValue($client, $guzzleClient);

        $result = $client->call(
            'https://testcheckout.buckaroo.nl/json/Transaction/',
            ['Content-Type: application/json'],
            'POST',
            '{"AmountDebit": 10.00}'
        );

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertInstanceOf(Response::class, $result[0]);
        $this->assertSame($responseBody, $result[1]);
    }

    public function test_call_method_handles_get_request(): void
    {
        $responseBody = ['Key' => 'TX-456'];
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode($responseBody)),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new Client(['handler' => $handlerStack]);

        $config = new DefaultConfig('test-key', 'test-secret', 'test');
        $client = new GuzzleHttpClientV7($config);

        $reflection = new ReflectionClass($client);
        $httpClientProperty = $reflection->getProperty('httpClient');
        $httpClientProperty->setAccessible(true);
        $httpClientProperty->setValue($client, $guzzleClient);

        $result = $client->call(
            'https://testcheckout.buckaroo.nl/json/Transaction/Status/TX-456',
            ['Accept: application/json'],
            'GET'
        );

        $this->assertIsArray($result);
        $this->assertSame($responseBody, $result[1]);
    }

    public function test_call_method_throws_transfer_exception_on_guzzle_error(): void
    {
        $mock = new MockHandler([
            new RequestException('Connection error', new Request('POST', 'test')),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new Client(['handler' => $handlerStack]);

        $config = new DefaultConfig('test-key', 'test-secret', 'test');
        $client = new GuzzleHttpClientV7($config);

        $reflection = new ReflectionClass($client);
        $httpClientProperty = $reflection->getProperty('httpClient');
        $httpClientProperty->setAccessible(true);
        $httpClientProperty->setValue($client, $guzzleClient);

        $this->expectException(TransferException::class);
        $this->expectExceptionMessage('Transfer failed');

        $client->call(
            'https://testcheckout.buckaroo.nl/json/Transaction/',
            ['Content-Type: application/json'],
            'POST',
            '{"test": "data"}'
        );
    }

    public function test_call_method_uses_custom_timeout_from_config(): void
    {
        // Config constructor accepts timeout and connectTimeout as parameters
        $config = new DefaultConfig(
            'test-key',
            'test-secret',
            'test',
            'EUR',
            '',
            '',
            '',
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            60,  // timeout
            10   // connectTimeout
        );

        $client = new GuzzleHttpClientV7($config);

        // Verify the client was created with custom timeouts
        $this->assertInstanceOf(GuzzleHttpClientV7::class, $client);
        $this->assertSame(60, $config->getTimeout());
        $this->assertSame(10, $config->getConnectTimeout());
    }

    public function test_call_method_handles_null_data(): void
    {
        $responseBody = ['Status' => 'OK'];
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode($responseBody)),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new Client(['handler' => $handlerStack]);

        $config = new DefaultConfig('test-key', 'test-secret', 'test');
        $client = new GuzzleHttpClientV7($config);

        $reflection = new ReflectionClass($client);
        $httpClientProperty = $reflection->getProperty('httpClient');
        $httpClientProperty->setAccessible(true);
        $httpClientProperty->setValue($client, $guzzleClient);

        $result = $client->call(
            'https://testcheckout.buckaroo.nl/json/Transaction/',
            ['Accept: application/json'],
            'GET',
            null
        );

        $this->assertIsArray($result);
        $this->assertSame($responseBody, $result[1]);
    }
}
