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
class MarketplacesTest extends FeatureTestCase
{
    /** @test */
    public function it_splits_marketplace_transaction(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Split executed'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'Marketplaces',
                        'Action' => 'Split',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('marketplaces')->split([
            'marketplace' => [
                'amount' => 10.00,
                'description' => 'Marketplace fee',
            ],
            'sellers' => [
                [
                    'accountId' => 'SELLER-001',
                    'amount' => 90.00,
                    'description' => 'Payment to seller 1',
                ],
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_splits_with_multiple_sellers(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Split executed'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'Marketplaces',
                        'Action' => 'Split',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('marketplaces')->split([
            'marketplace' => [
                'amount' => 15.00,
                'description' => 'Platform commission',
            ],
            'sellers' => [
                [
                    'accountId' => 'SELLER-001',
                    'amount' => 50.00,
                    'description' => 'Payment to seller 1',
                ],
                [
                    'accountId' => 'SELLER-002',
                    'amount' => 35.00,
                    'description' => 'Payment to seller 2',
                ],
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_transfers_marketplace_funds(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S002', 'Description' => 'Transfer executed'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'Marketplaces',
                        'Action' => 'Transfer',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('marketplaces')->transfer([
            'marketplace' => [
                'amount' => 5.00,
                'description' => 'Transfer fee',
            ],
            'sellers' => [
                [
                    'accountId' => 'SELLER-TRANSFER',
                    'amount' => 95.00,
                    'description' => 'Seller transfer payment',
                ],
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_processes_refund_supplementary(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S003', 'Description' => 'Refund supplementary processed'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'Marketplaces',
                        'Action' => 'RefundSupplementary',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('marketplaces')->refundSupplementary([
            'marketplace' => [
                'amount' => 10.00,
                'description' => 'Marketplace refund',
            ],
            'sellers' => [
                [
                    'accountId' => 'SELLER-REFUND',
                    'amount' => 90.00,
                    'description' => 'Seller refund portion',
                ],
            ],
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
                        'Name' => 'Marketplaces',
                        'Action' => 'Split',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('marketplaces')->split([
            'marketplace' => [
                'amount' => 10.00,
                'description' => 'Test',
            ],
            'sellers' => [
                [
                    'accountId' => 'SELLER-001',
                    'amount' => 90.00,
                    'description' => 'Test seller',
                ],
            ],
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
