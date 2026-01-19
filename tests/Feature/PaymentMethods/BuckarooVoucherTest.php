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
class BuckarooVoucherTest extends TestCase
{
    /** @test */
    public function it_creates_a_voucher_payment(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Payment successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'buckaroovoucher',
                        'Action' => 'Pay',
                        'Parameters' => [
                            ['Name' => 'vouchercode', 'Value' => 'VOUCHER-123456'],
                        ],
                    ]
                ],
                'Invoice' => 'INV-VOUCHER-001',
                'Currency' => 'EUR',
                'AmountDebit' => 50.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('buckaroovoucher')->pay([
            'amountDebit' => 50.00,
            'invoice' => 'INV-VOUCHER-001',
            'vouchercode' => 'VOUCHER-123456',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-VOUCHER-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(50.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_a_voucher_pay_remainder(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Payment successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'buckaroovoucher',
                        'Action' => 'PayRemainder',
                        'Parameters' => [
                            ['Name' => 'vouchercode', 'Value' => 'VOUCHER-123456'],
                        ],
                    ]
                ],
                'Invoice' => 'INV-REMAINDER-001',
                'Currency' => 'EUR',
                'AmountDebit' => 25.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('buckaroovoucher')->payRemainder([
            'amountDebit' => 25.00,
            'invoice' => 'INV-REMAINDER-001',
            'vouchercode' => 'VOUCHER-123456',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-REMAINDER-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(25.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_retrieves_voucher_balance(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Balance retrieved'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'buckaroovoucher',
                        'Action' => 'GetBalance',
                        'Parameters' => [
                            ['Name' => 'vouchercode', 'Value' => 'VOUCHER-123456'],
                            ['Name' => 'balance', 'Value' => '75.00'],
                        ],
                    ]
                ],
                'Invoice' => 'INV-BALANCE-001',
                'Currency' => 'EUR',
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('buckaroovoucher')->getBalance([
            'vouchercode' => 'VOUCHER-123456',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-BALANCE-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_a_new_voucher(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Voucher created'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'buckaroovoucher',
                        'Action' => 'CreateApplication',
                        'Parameters' => [
                            ['Name' => 'groupReference', 'Value' => 'GROUP-001'],
                            ['Name' => 'usageType', 'Value' => 'SINGLE'],
                            ['Name' => 'validFrom', 'Value' => '2026-01-01'],
                            ['Name' => 'validUntil', 'Value' => '2026-12-31'],
                            ['Name' => 'creationBalance', 'Value' => '100.00'],
                        ],
                    ]
                ],
                'Invoice' => 'INV-CREATE-001',
                'Currency' => 'EUR',
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('buckaroovoucher')->create([
            'groupReference' => 'GROUP-001',
            'usageType' => 'SINGLE',
            'validFrom' => '2026-01-01',
            'validUntil' => '2026-12-31',
            'creationBalance' => '100.00',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-CREATE-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_deactivates_a_voucher(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Voucher deactivated'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'buckaroovoucher',
                        'Action' => 'DeactivateVoucher',
                        'Parameters' => [
                            ['Name' => 'vouchercode', 'Value' => 'VOUCHER-123456'],
                        ],
                    ]
                ],
                'Invoice' => 'INV-DEACTIVATE-001',
                'Currency' => 'EUR',
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('buckaroovoucher')->deactivate([
            'vouchercode' => 'VOUCHER-123456',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-DEACTIVATE-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
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
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => $statusCode, 'Description' => 'Status'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Sub status'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'buckaroovoucher',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-STATUS-001',
                'Currency' => 'EUR',
                'AmountDebit' => 10.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('buckaroovoucher')->pay([
            'amountDebit' => 10.00,
            'invoice' => 'INV-STATUS-001',
            'vouchercode' => 'VOUCHER-123456',
        ]);

        if ($assertMethod === 'getStatusCode') {
            $this->assertEquals($statusCode, $response->getStatusCode());
        } else {
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
            'cancelled' => [890, 'isCanceled'],
            'technical_error' => [492, 'getStatusCode'],
            'waiting_on_consumer' => [792, 'getStatusCode'],
        ];
    }
}
