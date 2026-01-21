<?php

declare(strict_types=1);

namespace Tests\Feature\PaymentMethods;

use Tests\FeatureTestCase;
use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class SubscriptionsTest extends FeatureTestCase
{
    /** @test */
    public function it_creates_subscription(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Subscription created'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'Subscriptions',
                        'Action' => 'CreateSubscription',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('subscriptions')->create([
            'configurationCode' => 'CONFIG-123',
            'includeTransaction' => false,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_combined_subscription(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Combined subscription created'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'Subscriptions',
                        'Action' => 'CreateCombinedSubscription',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('subscriptions')->createCombined([
            'configurationCode' => 'CONFIG-456',
            'includeTransaction' => true,
            'debtor' => [
                'code' => 'DEBTOR-001',
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_updates_subscription(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S002', 'Description' => 'Subscription updated'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'Subscriptions',
                        'Action' => 'UpdateSubscription',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('subscriptions')->update([
            'subscriptionGuid' => 'SUBSCRIPTION-GUID-123',
            'configurationCode' => 'CONFIG-789',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_updates_combined_subscription(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S003', 'Description' => 'Combined subscription updated'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'Subscriptions',
                        'Action' => 'UpdateCombinedSubscription',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('subscriptions')->updateCombined([
            'subscriptionGuid' => 'SUBSCRIPTION-GUID-456',
            'configurationCode' => 'CONFIG-UPDATED',
            'startRecurrent' => true,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_stops_subscription(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S004', 'Description' => 'Subscription stopped'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'Subscriptions',
                        'Action' => 'StopSubscription',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('subscriptions')->stop([
            'subscriptionGuid' => 'SUBSCRIPTION-GUID-789',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_retrieves_subscription_info(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S005', 'Description' => 'Subscription info retrieved'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'Subscriptions',
                        'Action' => 'SubscriptionInfo',
                        'Parameters' => [
                            ['Name' => 'SubscriptionGuid', 'Value' => 'SUBSCRIPTION-GUID-INFO'],
                            ['Name' => 'Status', 'Value' => 'Active'],
                        ],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('subscriptions')->info([
            'subscriptionGuid' => 'SUBSCRIPTION-GUID-INFO',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());

        $params = $response->getServiceParameters();
        $this->assertEquals('SUBSCRIPTION-GUID-INFO', $params['subscriptionguid']);
        $this->assertEquals('Active', $params['status']);
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_deletes_payment_configuration(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S006', 'Description' => 'Payment configuration deleted'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'Subscriptions',
                        'Action' => 'DeletePaymentConfiguration',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('subscriptions')->deletePaymentConfig([
            'configurationCode' => 'CONFIG-TO-DELETE',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_pauses_subscription(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S007', 'Description' => 'Subscription paused'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'Subscriptions',
                        'Action' => 'PauseSubscription',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('subscriptions')->pause([
            'subscriptionGuid' => 'SUBSCRIPTION-GUID-PAUSE',
            'pauseDate' => '2026-02-01',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_resumes_subscription(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S008', 'Description' => 'Subscription resumed'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'Subscriptions',
                        'Action' => 'ResumeSubscription',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('subscriptions')->resume([
            'subscriptionGuid' => 'SUBSCRIPTION-GUID-RESUME',
            'resumeDate' => '2026-03-01',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertTrue($response->get('IsTest'));
    }

    /**
     * @test
     * @dataProvider statusCodeProvider
     */
    public function it_handles_various_status_codes(int $statusCode, string $assertMethod): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => $statusCode, 'Description' => 'Status'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Sub status'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'Subscriptions',
                        'Action' => 'CreateSubscription',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('subscriptions')->create([
            'configurationCode' => 'CONFIG-STATUS-TEST',
        ]);

        $this->assertTrue($response->$assertMethod());
    }

    public static function statusCodeProvider(): array
    {
        return [
            'success' => [190, 'isSuccess'],
            'failed' => [490, 'isFailed'],
            'validation_failure' => [491, 'isValidationFailure'],
            'rejected' => [690, 'isRejected'],
            'waiting_on_user_input' => [790, 'isWaitingOnUserInput'],
            'pending_processing' => [791, 'isPendingProcessing'],
            'cancelled' => [890, 'isCanceled'],
        ];
    }
}
