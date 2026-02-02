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
class KlarnaPayTest extends FeatureTestCase
{
    /** @test */
    public function it_creates_pay_transaction(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Transaction successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'klarna',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-KLARNA-001',
                'Currency' => 'EUR',
                'AmountDebit' => 150.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('klarna')->pay([
            'amountDebit' => 150.00,
            'invoice' => 'INV-KLARNA-001',
            'currency' => 'EUR',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-KLARNA-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(150.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_pay_in_installments_transaction(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Installment payment successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'klarna',
                        'Action' => 'PayInInstallments',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-KLARNA-INST-001',
                'Currency' => 'EUR',
                'AmountDebit' => 200.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('klarna')->payInInstallments([
            'amountDebit' => 200.00,
            'invoice' => 'INV-KLARNA-INST-001',
            'currency' => 'EUR',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-KLARNA-INST-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(200.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_pay_remainder_transaction(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $originalTxKey = 'ORIG_KLARNA_TX_KEY';

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Remainder payment successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'klarna',
                        'Action' => 'PayRemainder',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-KLARNA-REM-001',
                'Currency' => 'EUR',
                'AmountDebit' => 75.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('klarna')->payRemainder([
            'amountDebit' => 75.00,
            'invoice' => 'INV-KLARNA-REM-001',
            'originalTransactionKey' => $originalTxKey,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-KLARNA-REM-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(75.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_refunds_payment(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $originalTxKey = 'ORIG_KLARNA_TX_KEY';

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Refund successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'klarna',
                        'Action' => 'Refund',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-KLARNA-REFUND-001',
                'Currency' => 'EUR',
                'AmountCredit' => 50.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('klarna')->refund([
            'amountCredit' => 50.00,
            'invoice' => 'INV-KLARNA-REFUND-001',
            'originalTransactionKey' => $originalTxKey,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-KLARNA-REFUND-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(50.00, $response->getAmountCredit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_processes_payment_with_billing_and_articles(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Transaction successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'klarna',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-KLARNA-FULL-001',
                'Currency' => 'EUR',
                'AmountDebit' => 125.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('klarna')->pay([
            'amountDebit' => 125.00,
            'invoice' => 'INV-KLARNA-FULL-001',
            'currency' => 'EUR',
            'billing' => [
                'recipient' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ],
                'address' => [
                    'street' => 'Main Street',
                    'houseNumber' => '123',
                    'zipcode' => '1234AB',
                    'city' => 'Amsterdam',
                    'country' => 'NL',
                ],
                'phone' => [
                    'mobile' => '0612345678',
                ],
                'email' => 'john.doe@example.com',
            ],
            'articles' => [
                [
                    'identifier' => 'PROD-001',
                    'description' => 'Test Product',
                    'quantity' => 2,
                    'price' => 50.00,
                    'vatPercentage' => 21,
                ],
                [
                    'identifier' => 'PROD-002',
                    'description' => 'Another Product',
                    'quantity' => 1,
                    'price' => 25.00,
                    'vatPercentage' => 21,
                ],
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-KLARNA-FULL-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(125.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_processes_payment_with_separate_shipping_address(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Transaction successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'klarna',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-KLARNA-SHIP-001',
                'Currency' => 'EUR',
                'AmountDebit' => 100.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('klarna')->pay([
            'amountDebit' => 100.00,
            'invoice' => 'INV-KLARNA-SHIP-001',
            'currency' => 'EUR',
            'billing' => [
                'recipient' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ],
                'address' => [
                    'street' => 'Main Street',
                    'houseNumber' => '123',
                    'zipcode' => '1234AB',
                    'city' => 'Amsterdam',
                    'country' => 'NL',
                ],
            ],
            'shipping' => [
                'recipient' => [
                    'firstName' => 'Jane',
                    'lastName' => 'Smith',
                ],
                'address' => [
                    'street' => 'Other Street',
                    'houseNumber' => '456',
                    'zipcode' => '5678CD',
                    'city' => 'Rotterdam',
                    'country' => 'NL',
                ],
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-KLARNA-SHIP-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(100.00, $response->getAmountDebit());
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
                        'Name' => 'klarna',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-KLARNA-STATUS-001',
                'Currency' => 'EUR',
                'AmountDebit' => 10.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('klarna')->pay([
            'amountDebit' => 10.00,
            'invoice' => 'INV-KLARNA-STATUS-001',
            'currency' => 'EUR',
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
