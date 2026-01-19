<?php

declare(strict_types=1);

namespace Tests\Feature\PaymentMethods;

use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class BuckarooWalletTest extends TestCase
{
    /** @test */
    public function it_creates_wallet(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Wallet created'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'BuckarooWalletCollecting',
                        'Action' => 'Create',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('buckaroo_wallet')->createWallet([
            'currency' => 'EUR',
            'customer' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
    }

    /** @test */
    public function it_updates_wallet(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S002', 'Description' => 'Wallet updated'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'BuckarooWalletCollecting',
                        'Action' => 'Update',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('buckaroo_wallet')->updateWallet([
            'walletId' => 'WALLET-123',
            'customer' => [
                'firstName' => 'Jane',
                'lastName' => 'Smith',
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
    }

    /** @test */
    public function it_retrieves_wallet_info(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S003', 'Description' => 'Wallet info retrieved'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'BuckarooWalletCollecting',
                        'Action' => 'GetInfo',
                        'Parameters' => [
                            ['Name' => 'WalletId', 'Value' => 'WALLET-INFO-123'],
                            ['Name' => 'Status', 'Value' => 'Active'],
                        ],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('buckaroo_wallet')->getInfo([
            'walletId' => 'WALLET-INFO-123',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());

        $params = $response->getServiceParameters();
        $this->assertEquals('WALLET-INFO-123', $params['walletid']);
        $this->assertEquals('Active', $params['status']);
    }

    /** @test */
    public function it_releases_wallet_funds(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S004', 'Description' => 'Funds released'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'BuckarooWalletCollecting',
                        'Action' => 'Release',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('buckaroo_wallet')->release([
            'amountCredit' => 25.50,
            'walletId' => 'WALLET-RELEASE-123',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
    }

    /** @test */
    public function it_deposits_to_wallet(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S005', 'Description' => 'Deposit successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'BuckarooWalletCollecting',
                        'Action' => 'Deposit',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
                'Invoice' => 'INV-DEPOSIT-123',
                'AmountCredit' => 50.00,
            ]),
        ]);

        $response = $this->buckaroo->method('buckaroo_wallet')->deposit([
            'invoice' => 'INV-DEPOSIT-123',
            'amountCredit' => 50.00,
            'walletId' => 'WALLET-DEPOSIT-123',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-DEPOSIT-123', $response->getInvoice());
    }

    /** @test */
    public function it_reserves_wallet_funds(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S006', 'Description' => 'Funds reserved'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'BuckarooWalletCollecting',
                        'Action' => 'Reserve',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
                'Invoice' => 'INV-RESERVE-123',
                'AmountCredit' => 75.00,
            ]),
        ]);

        $response = $this->buckaroo->method('buckaroo_wallet')->reserve([
            'invoice' => 'INV-RESERVE-123',
            'amountCredit' => 75.00,
            'walletId' => 'WALLET-RESERVE-123',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-RESERVE-123', $response->getInvoice());
    }

    /** @test */
    public function it_withdraws_from_wallet(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S007', 'Description' => 'Withdrawal successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'BuckarooWalletCollecting',
                        'Action' => 'Withdrawal',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('buckaroo_wallet')->withdrawal([
            'walletId' => 'WALLET-WITHDRAW-123',
            'bankAccount' => [
                'iban' => 'NL13TEST0123456789',
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
    }

    /** @test */
    public function it_cancels_wallet_transaction(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S008', 'Description' => 'Transaction cancelled'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'BuckarooWalletCollecting',
                        'Action' => 'Cancel',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('buckaroo_wallet')->cancel([
            'walletId' => 'WALLET-CANCEL-123',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
    }

    /** @test */
    public function it_processes_wallet_payment(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S009', 'Description' => 'Payment successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'BuckarooWalletCollecting',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
                'Invoice' => 'INV-PAY-123',
                'AmountDebit' => 100.00,
            ]),
        ]);

        $response = $this->buckaroo->method('buckaroo_wallet')->pay([
            'invoice' => 'INV-PAY-123',
            'amountDebit' => 100.00,
            'walletId' => 'WALLET-PAY-123',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-PAY-123', $response->getInvoice());
    }

    /**
     * @test
     * @dataProvider statusCodeProvider
     */
    public function it_handles_various_status_codes(int $statusCode, string $assertMethod): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => $statusCode, 'Description' => 'Status'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Sub status'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'BuckarooWalletCollecting',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
                'Invoice' => 'INV-STATUS-TEST',
            ]),
        ]);

        $response = $this->buckaroo->method('buckaroo_wallet')->pay([
            'invoice' => 'INV-STATUS-TEST',
            'amountDebit' => 10.00,
            'walletId' => 'WALLET-STATUS-TEST',
        ]);

        if ($assertMethod === 'getStatusCode')
        {
            $this->assertEquals($statusCode, $response->getStatusCode());
        } else
        {
            $this->assertTrue($response->$assertMethod());
        }
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
