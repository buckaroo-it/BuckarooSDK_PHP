<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use Buckaroo\Services\ActiveSubscriptions;
use Tests\Support\BuckarooMockRequest;
use Tests\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ActiveSubscriptionsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->useMock();
    }

    public function test_it_returns_active_subscriptions(): void
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

        $service = new ActiveSubscriptions($this->buckaroo->client());

        $subscriptions = $service->get();

        $this->assertIsArray($subscriptions);
        $this->assertCount(1, $subscriptions);
        $this->assertArrayHasKey('ratePlanCode', $subscriptions[0]);
        $this->assertSame('PLAN-001', $subscriptions[0]['ratePlanCode']);
        $this->assertSame('SUB-001', $subscriptions[0]['subscriptionGuid']);
        $this->assertIsArray($subscriptions[0]['currencies']);
        $this->assertContains('EUR', $subscriptions[0]['currencies']);
        $this->assertContains('USD', $subscriptions[0]['currencies']);
    }

    public function test_it_returns_multiple_subscriptions(): void
    {
        $xmlResponse = '<?xml version="1.0" encoding="UTF-8"?>
<ArrayOfServiceCurrencies>
    <ServiceCurrencies>
        <RatePlanCode>PLAN-001</RatePlanCode>
        <SubscriptionGuid>SUB-001</SubscriptionGuid>
        <Currencies>
            <string>EUR</string>
        </Currencies>
    </ServiceCurrencies>
    <ServiceCurrencies>
        <RatePlanCode>PLAN-002</RatePlanCode>
        <SubscriptionGuid>SUB-002</SubscriptionGuid>
        <Currencies>
            <string>USD</string>
        </Currencies>
    </ServiceCurrencies>
</ArrayOfServiceCurrencies>';

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'POST',
                '*/DataRequest*',
                [
                    'Key' => 'DATARESPONSE-002',
                    'Status' => [
                        'Code' => ['Code' => 190],
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

        $service = new ActiveSubscriptions($this->buckaroo->client());

        $subscriptions = $service->get();

        $this->assertCount(2, $subscriptions);
        $this->assertSame('PLAN-001', $subscriptions[0]['ratePlanCode']);
        $this->assertSame('PLAN-002', $subscriptions[1]['ratePlanCode']);
    }

    public function test_it_returns_empty_array_when_no_subscriptions(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'POST',
                '*/DataRequest*',
                [
                    'Key' => 'DATARESPONSE-003',
                    'Status' => [
                        'Code' => ['Code' => 190],
                    ],
                    'Services' => [],
                ]
            ),
        ]);

        $service = new ActiveSubscriptions($this->buckaroo->client());

        $subscriptions = $service->get();

        $this->assertIsArray($subscriptions);
        $this->assertEmpty($subscriptions);
    }

    public function test_it_handles_single_currency_as_array(): void
    {
        $xmlResponse = '<?xml version="1.0" encoding="UTF-8"?>
<ArrayOfServiceCurrencies>
    <ServiceCurrencies>
        <RatePlanCode>PLAN-SINGLE</RatePlanCode>
        <SubscriptionGuid>SUB-SINGLE</SubscriptionGuid>
        <Currencies>
            <string>EUR</string>
        </Currencies>
    </ServiceCurrencies>
</ArrayOfServiceCurrencies>';

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'POST',
                '*/DataRequest*',
                [
                    'Key' => 'DATARESPONSE-004',
                    'Status' => [
                        'Code' => ['Code' => 190],
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

        $service = new ActiveSubscriptions($this->buckaroo->client());

        $subscriptions = $service->get();

        // Single currency should still be returned as an array
        $this->assertIsArray($subscriptions[0]['currencies']);
        $this->assertCount(1, $subscriptions[0]['currencies']);
        $this->assertSame('EUR', $subscriptions[0]['currencies'][0]);
    }

    public function test_it_converts_keys_to_camel_case(): void
    {
        $xmlResponse = '<?xml version="1.0" encoding="UTF-8"?>
<ArrayOfServiceCurrencies>
    <ServiceCurrencies>
        <RatePlanCode>PLAN-CC</RatePlanCode>
        <SubscriptionGuid>SUB-CC</SubscriptionGuid>
        <SomeOtherField>value</SomeOtherField>
    </ServiceCurrencies>
</ArrayOfServiceCurrencies>';

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'POST',
                '*/DataRequest*',
                [
                    'Key' => 'DATARESPONSE-005',
                    'Status' => [
                        'Code' => ['Code' => 190],
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

        $service = new ActiveSubscriptions($this->buckaroo->client());

        $subscriptions = $service->get();

        // Keys should be camelCase (first letter lowercase)
        $this->assertArrayHasKey('ratePlanCode', $subscriptions[0]);
        $this->assertArrayHasKey('subscriptionGuid', $subscriptions[0]);
        $this->assertArrayHasKey('someOtherField', $subscriptions[0]);
    }

    public function test_it_returns_empty_array_on_exception(): void
    {
        // Empty response without proper structure should return empty array
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'POST',
                '*/DataRequest*',
                []
            ),
        ]);

        $service = new ActiveSubscriptions($this->buckaroo->client());

        $subscriptions = $service->get();

        $this->assertIsArray($subscriptions);
        $this->assertEmpty($subscriptions);
    }
}
