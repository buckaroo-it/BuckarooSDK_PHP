<?php

declare(strict_types=1);

namespace Tests\Unit;

use Buckaroo\BuckarooClient;
use Buckaroo\Config\Config;
use Buckaroo\Config\DefaultConfig;
use Buckaroo\Exceptions\BuckarooException;
use Buckaroo\Handlers\Logging\Observer;
use Buckaroo\PaymentMethods\BatchTransactions;
use Buckaroo\PaymentMethods\PaymentFacade;
use Buckaroo\Services\TransactionService;
use Buckaroo\Transaction\Client;
use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class BuckarooClientTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->useMock();
    }

    public function test_creates_client_with_string_credentials(): void
    {
        $client = new BuckarooClient('test-key', 'test-secret', 'test');

        $internalClient = $client->client();
        $config = $internalClient->config();

        $this->assertInstanceOf(Client::class, $internalClient);
        $this->assertInstanceOf(Config::class, $config);
        $this->assertSame('test-key', $config->websiteKey());
        $this->assertSame('test-secret', $config->secretKey());
        $this->assertSame('test', $config->mode());
    }

    public function test_creates_client_with_config_object(): void
    {
        $config = new DefaultConfig('config-key', 'config-secret', 'live');

        $client = new BuckarooClient($config);

        $internalClient = $client->client();
        $retrievedConfig = $internalClient->config();

        $this->assertSame($config, $retrievedConfig);
        $this->assertSame('config-key', $retrievedConfig->websiteKey());
        $this->assertSame('config-secret', $retrievedConfig->secretKey());
        $this->assertSame('live', $retrievedConfig->mode());
    }

    public function test_creates_client_with_different_modes(): void
    {
        $defaultClient = new BuckarooClient('test-key', 'test-secret');
        $this->assertSame('test', $defaultClient->client()->config()->mode());
        $this->assertFalse($defaultClient->client()->config()->isLiveMode());

        $testClient = new BuckarooClient('test-key', 'test-secret', 'test');
        $this->assertSame('test', $testClient->client()->config()->mode());
        $this->assertFalse($testClient->client()->config()->isLiveMode());

        $liveClient = new BuckarooClient('live-key', 'live-secret', 'live');
        $this->assertSame('live', $liveClient->client()->config()->mode());
        $this->assertTrue($liveClient->client()->config()->isLiveMode());
    }

    public function test_throws_exception_when_credentials_missing(): void
    {
        $this->expectException(BuckarooException::class);
        $this->expectExceptionMessage('Config is missing');

        new BuckarooClient('', '');
    }

    public function test_creates_payment_methods(): void
    {
        $client = new BuckarooClient('test-key', 'test-secret');

        $ideal = $client->method('ideal');
        $this->assertInstanceOf(PaymentFacade::class, $ideal);
        $this->assertSame('ideal', $ideal->paymentMethod()->paymentName());

        $creditcard = $client->method('creditcard');
        $this->assertInstanceOf(PaymentFacade::class, $creditcard);

        $paypal = $client->method('paypal');
        $this->assertInstanceOf(PaymentFacade::class, $paypal);
        $this->assertSame('paypal', $paypal->paymentMethod()->paymentName());

        $generic = $client->method(null);
        $this->assertInstanceOf(PaymentFacade::class, $generic);
    }

    public function test_creates_transaction_service_with_transaction_key(): void
    {
        $client = new BuckarooClient('test-key', 'test-secret');

        $service = $client->transaction('TX-12345');

        $this->assertInstanceOf(TransactionService::class, $service);
    }

    public function test_passes_client_to_transaction_service(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'GET',
                '*/Transaction/Status/TX-12345',
                TestHelpers::successResponse([
                    'Key' => 'TX-12345',
                    'Status' => [
                        'Code' => ['Code' => 190],
                    ],
                ])
            ),
        ]);

        $client = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $service = $client->transaction('TX-12345');

        $response = $service->status();

        $this->assertTrue($response->isSuccess());
        $this->assertSame('TX-12345', $response->getTransactionKey());
    }

    public function test_returns_internal_client(): void
    {
        $client = new BuckarooClient('test-key', 'test-secret');

        $internalClient = $client->client();

        $this->assertInstanceOf(Client::class, $internalClient);
    }

    public function test_updates_config_on_client(): void
    {
        $client = new BuckarooClient('original-key', 'original-secret', 'test');

        $newConfig = new DefaultConfig('new-key', 'new-secret', 'live');
        $result = $client->setConfig($newConfig);

        $this->assertSame($client, $result);

        $retrievedConfig = $client->client()->config();
        $this->assertSame('new-key', $retrievedConfig->websiteKey());
        $this->assertSame('new-secret', $retrievedConfig->secretKey());
        $this->assertSame('live', $retrievedConfig->mode());
    }

    public function test_preserves_config_across_operations(): void
    {
        $config = new DefaultConfig('persistent-key', 'persistent-secret', 'test');
        $client = new BuckarooClient($config);

        $client->method('ideal');
        $client->transaction('TX-123');

        $retrievedConfig = $client->client()->config();

        $this->assertSame($config, $retrievedConfig);
        $this->assertSame('persistent-key', $retrievedConfig->websiteKey());
        $this->assertSame('persistent-secret', $retrievedConfig->secretKey());
    }

    public function test_validates_credentials_successfully(): void
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
                        ],
                    ],
                ],
                200
            ),
        ]);

        $client = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        $result = $client->confirmCredential();

        $this->assertTrue($result);
    }

    public function test_returns_false_for_invalid_credentials(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'GET',
                '*/Specification/ideal*',
                [
                    'error' => 'Invalid credentials',
                ],
                401
            ),
        ]);

        $client = new BuckarooClient('invalid-key', 'invalid-secret');

        $result = $client->confirmCredential();

        $this->assertFalse($result);
    }

    public function test_attaches_logger_observer(): void
    {
        $client = new BuckarooClient('test-key', 'test-secret');

        $observer = new class implements Observer {
            public function handle(string $method, string $message, array $context = []): void
            {
            }
        };

        $result = $client->attachLogger($observer);

        $this->assertSame($client, $result);

        $config = $client->client()->config();
        $logger = $config->getLogger();

        $this->assertNotNull($logger);
    }

    public function test_creates_batch_transactions(): void
    {
        $client = new BuckarooClient('test-key', 'test-secret');

        $transactions = [
            ['AmountDebit' => 10.00, 'Invoice' => 'INV-001'],
            ['AmountDebit' => 20.00, 'Invoice' => 'INV-002'],
        ];

        $batch = $client->batch($transactions);

        $this->assertInstanceOf(BatchTransactions::class, $batch);
    }

    public function test_retrieves_active_subscriptions_list(): void
    {
        $xmlResponse = '<?xml version="1.0" encoding="UTF-8"?>
<ArrayOfServiceCurrencies>
    <ServiceCurrencies>
        <RatePlanCode>PLAN-001</RatePlanCode>
        <SubscriptionGuid>SUB-001</SubscriptionGuid>
        <Currencies>
            <string>EUR</string>
            <string>USD</string>
        </Currencies>
    </ServiceCurrencies>
</ArrayOfServiceCurrencies>';

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'POST',
                '*/DataRequest*',
                [
                    'Key' => 'DATARESPONSE-001',
                    'Status' => [
                        'Code' => ['Code' => 190],
                        'SubCode' => ['Code' => 'S001'],
                        'DateTime' => date('Y-m-d\TH:i:s'),
                    ],
                    'Services' => [
                        [
                            'Name' => 'GetActiveSubscriptions',
                            'Action' => 'GetActiveSubscriptions',
                            'Parameters' => [
                                [
                                    'Name' => 'activesubscriptions',
                                    'Value' => $xmlResponse,
                                ],
                            ],
                        ],
                    ],
                ]
            ),
        ]);

        $client = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        $subscriptions = $client->getActiveSubscriptions();

        $this->assertIsArray($subscriptions);
        $this->assertNotEmpty($subscriptions);
        $this->assertArrayHasKey('ratePlanCode', $subscriptions[0]);
        $this->assertSame('PLAN-001', $subscriptions[0]['ratePlanCode']);
    }

    public function test_handles_empty_subscriptions_response(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'POST',
                '*/DataRequest/',
                [
                    'ServiceParameters' => [],
                ]
            ),
        ]);

        $client = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        $subscriptions = $client->getActiveSubscriptions();

        $this->assertIsArray($subscriptions);
        $this->assertEmpty($subscriptions);
    }
}
