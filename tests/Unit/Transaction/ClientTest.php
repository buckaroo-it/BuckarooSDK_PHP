<?php

declare(strict_types=1);

namespace Tests\Unit\Transaction;

use Buckaroo\Config\DefaultConfig;
use Buckaroo\Transaction\Client;
use Buckaroo\Transaction\Request\TransactionRequest;
use Buckaroo\Transaction\Response\Response;
use Buckaroo\Transaction\Response\TransactionResponse;
use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ClientTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->useMock();
    }

    public function test_creates_client_with_config(): void
    {
        $config = new DefaultConfig('test-key', 'test-secret', 'test');
        $client = new Client($config);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertSame($config, $client->config());
    }

    public function test_builds_endpoint_urls_in_test_mode(): void
    {
        $config = new DefaultConfig('test-key', 'test-secret', 'test');
        $client = new Client($config);

        $baseUrl = 'https://testcheckout.buckaroo.nl';

        $endpoints = [
            'json/Transaction/' => "{$baseUrl}/json/Transaction/",
            'json/Transaction/Status/TX-12345' => "{$baseUrl}/json/Transaction/Status/TX-12345",
            'json/Transaction/RefundInfo/TX-REFUND-001' => "{$baseUrl}/json/Transaction/RefundInfo/TX-REFUND-001",
            'json/Transaction/Cancel/TX-CANCEL-123' => "{$baseUrl}/json/Transaction/Cancel/TX-CANCEL-123",
            'json/DataRequest/' => "{$baseUrl}/json/DataRequest/",
            'json/batch/Transactions' => "{$baseUrl}/json/batch/Transactions",
            'json/batch/DataRequests' => "{$baseUrl}/json/batch/DataRequests",
            'json/Transaction/Specification/ideal?serviceVersion=2' => "{$baseUrl}/json/Transaction/Specification/ideal?serviceVersion=2",
        ];

        foreach ($endpoints as $path => $expected) {
            $this->assertSame($expected, $client->getEndpoint($path));
        }
    }

    public function test_builds_endpoint_urls_in_live_mode(): void
    {
        $config = new DefaultConfig('live-key', 'live-secret', 'live');
        $client = new Client($config);

        $endpoint = $client->getEndpoint('json/Transaction/');

        $this->assertSame('https://checkout.buckaroo.nl/json/Transaction/', $endpoint);
    }

    public function test_switches_between_test_and_live_mode(): void
    {
        $testConfig = new DefaultConfig('test-key', 'test-secret', 'test');
        $testClient = new Client($testConfig);

        $liveConfig = new DefaultConfig('live-key', 'live-secret', 'live');
        $liveClient = new Client($liveConfig);

        $testEndpoint = $testClient->getEndpoint('json/Transaction/');
        $liveEndpoint = $liveClient->getEndpoint('json/Transaction/');

        $this->assertSame('https://testcheckout.buckaroo.nl/json/Transaction/', $testEndpoint);
        $this->assertSame('https://checkout.buckaroo.nl/json/Transaction/', $liveEndpoint);
        $this->assertNotSame($testEndpoint, $liveEndpoint);
    }

    public function test_returns_transaction_url(): void
    {
        $config = new DefaultConfig('test-key', 'test-secret', 'test');
        $client = new Client($config);

        $endpoint = $client->getTransactionUrl();

        $this->assertSame('https://testcheckout.buckaroo.nl/json/Transaction/', $endpoint);
    }

    public function test_sends_get_request_to_endpoint(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'GET',
                "*/Transaction/Status/{$transactionKey}",
                TestHelpers::successResponse(['Key' => $transactionKey])
            ),
        ]);

        $client = $this->buckaroo->client();
        $response = $client->get(Response::class, $client->getEndpoint("json/Transaction/Status/{$transactionKey}"));

        $this->assertInstanceOf(Response::class, $response);
        $data = $response->toArray();
        $this->assertSame($transactionKey, $data['Key']);
    }

    public function test_uses_default_transaction_endpoint_for_get(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'GET',
                '*/Transaction/',
                TestHelpers::successResponse()
            ),
        ]);

        $client = $this->buckaroo->client();
        $response = $client->get();

        $this->assertInstanceOf(Response::class, $response);
    }

    public function test_get_request_returns_custom_response_class(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'GET',
                '*/Transaction/',
                TestHelpers::successResponse()
            ),
        ]);

        $client = $this->buckaroo->client();
        $response = $client->get(TransactionResponse::class);

        $this->assertInstanceOf(TransactionResponse::class, $response);
    }

    public function test_sends_post_request_with_payload(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'POST',
                '*/Transaction/',
                TestHelpers::successResponse([
                    'Key' => $transactionKey,
                    'Invoice' => 'INV-POST-001',
                ])
            ),
        ]);

        $request = new TransactionRequest();
        $request->setData('AmountDebit', 25.00);
        $request->setData('Invoice', 'INV-POST-001');

        $client = $this->buckaroo->client();
        $response = $client->post($request);

        $this->assertInstanceOf(TransactionResponse::class, $response);
        $this->assertTrue($response->isSuccess());
        $this->assertSame($transactionKey, $response->getTransactionKey());
        $this->assertSame('INV-POST-001', $response->getInvoice());
    }

    public function test_post_request_handles_empty_payload(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'POST',
                '*/Transaction/',
                TestHelpers::successResponse()
            ),
        ]);

        $client = $this->buckaroo->client();
        $response = $client->post(null);

        $this->assertInstanceOf(TransactionResponse::class, $response);
        $this->assertTrue($response->isSuccess());
    }

    public function test_sends_batch_transaction_request(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'POST',
                '*/batch/Transactions',
                TestHelpers::successResponse(['Key' => $transactionKey])
            ),
        ]);

        $request = new TransactionRequest();
        $request->setData('Transactions', [
            ['AmountDebit' => 10.00, 'Invoice' => 'BATCH-1'],
            ['AmountDebit' => 20.00, 'Invoice' => 'BATCH-2'],
        ]);

        $client = $this->buckaroo->client();
        $response = $client->transactionBatchRequest($request);

        $this->assertInstanceOf(TransactionResponse::class, $response);
        $this->assertSame($transactionKey, $response->getTransactionKey());
    }

    public function test_sends_batch_data_request(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'POST',
                '*/batch/DataRequests',
                TestHelpers::successResponse()
            ),
        ]);

        $request = new TransactionRequest();
        $request->setData('DataRequests', [
            ['Type' => 'Status', 'TransactionKey' => TestHelpers::generateTransactionKey()],
            ['Type' => 'RefundInfo', 'TransactionKey' => TestHelpers::generateTransactionKey()],
        ]);

        $client = $this->buckaroo->client();
        $response = $client->dataBatchRequest($request);

        $this->assertInstanceOf(TransactionResponse::class, $response);
    }

    public function test_fetches_payment_method_specification(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'GET',
                '*/Specification/ideal*',
                [
                    'Services' => [
                        [
                            'Name' => 'ideal',
                            'Version' => 2,
                            'Actions' => ['Pay', 'Refund'],
                        ],
                    ],
                ]
            ),
        ]);

        $client = $this->buckaroo->client();
        $response = $client->specification('ideal', 2);

        $this->assertInstanceOf(TransactionResponse::class, $response);
    }

    public function test_specification_defaults_service_version_to_zero(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'GET',
                '*/Specification/paypal*',
                ['Services' => []]
            ),
        ]);

        $client = $this->buckaroo->client();
        $response = $client->specification('paypal');

        $this->assertInstanceOf(TransactionResponse::class, $response);
    }

    public function test_sends_data_request_to_data_endpoint(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'POST',
                '*/DataRequest/',
                TestHelpers::successResponse([
                    'Key' => $transactionKey,
                    'Status' => [
                        'Code' => ['Code' => 190],
                        'SubCode' => ['Code' => 'S001'],
                        'DateTime' => date('Y-m-d\TH:i:s'),
                    ],
                ])
            ),
        ]);

        $request = new TransactionRequest();
        $request->setData('Services', [
            'Name' => 'GetActiveSubscriptions',
            'Action' => 'GetActiveSubscriptions',
        ]);

        $client = $this->buckaroo->client();
        $response = $client->dataRequest($request);

        $this->assertInstanceOf(TransactionResponse::class, $response);
        $this->assertTrue($response->isSuccess());
        $this->assertSame($transactionKey, $response->getTransactionKey());
    }

    public function test_returns_current_config(): void
    {
        $config = new DefaultConfig('get-key', 'get-secret', 'test');
        $client = new Client($config);

        $retrievedConfig = $client->config();

        $this->assertSame($config, $retrievedConfig);
        $this->assertSame('get-key', $retrievedConfig->websiteKey());
        $this->assertSame('get-secret', $retrievedConfig->secretKey());
    }

    public function test_updates_config_dynamically(): void
    {
        $originalConfig = new DefaultConfig('original-key', 'original-secret', 'test');
        $client = new Client($originalConfig);

        $newConfig = new DefaultConfig('updated-key', 'updated-secret', 'live');
        $client->config($newConfig);

        $retrievedConfig = $client->config();

        $this->assertSame($newConfig, $retrievedConfig);
        $this->assertNotSame($originalConfig, $retrievedConfig);
        $this->assertSame('updated-key', $retrievedConfig->websiteKey());
        $this->assertSame('updated-secret', $retrievedConfig->secretKey());
        $this->assertSame('live', $retrievedConfig->mode());
    }
}
