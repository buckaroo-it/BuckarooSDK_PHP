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
class BillinkTest extends FeatureTestCase
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
                        'Name' => 'billink',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-BILLINK-001',
                'Currency' => 'EUR',
                'AmountDebit' => 250.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('billink')->pay([
            'amountDebit' => 250.00,
            'invoice' => 'INV-BILLINK-001',
            'currency' => 'EUR',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-BILLINK-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(250.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_pay_transaction_with_billing_data(): void
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
                        'Name' => 'billink',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-BILLINK-BILLING-001',
                'Currency' => 'EUR',
                'AmountDebit' => 175.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('billink')->pay([
            'amountDebit' => 175.00,
            'invoice' => 'INV-BILLINK-BILLING-001',
            'currency' => 'EUR',
            'billing' => [
                'recipient' => [
                    'category' => 'B2B',
                    'careOf' => 'Finance Department',
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'birthDate' => '1980-01-15',
                ],
                'address' => [
                    'street' => 'Kalverstraat',
                    'houseNumber' => '1',
                    'zipcode' => '1012NX',
                    'city' => 'Amsterdam',
                    'country' => 'NL',
                ],
                'phone' => [
                    'mobile' => '0612345678',
                ],
                'email' => 'john.doe@example.com',
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-BILLINK-BILLING-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(175.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_pay_transaction_with_separate_shipping_address(): void
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
                        'Name' => 'billink',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-BILLINK-SHIPPING-001',
                'Currency' => 'EUR',
                'AmountDebit' => 199.99,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('billink')->pay([
            'amountDebit' => 199.99,
            'invoice' => 'INV-BILLINK-SHIPPING-001',
            'currency' => 'EUR',
            'billing' => [
                'recipient' => [
                    'category' => 'B2C',
                    'firstName' => 'Jane',
                    'lastName' => 'Smith',
                ],
                'address' => [
                    'street' => 'Billing Street',
                    'houseNumber' => '10',
                    'zipcode' => '1000AA',
                    'city' => 'Amsterdam',
                    'country' => 'NL',
                ],
                'email' => 'jane.smith@example.com',
            ],
            'shipping' => [
                'recipient' => [
                    'category' => 'B2C',
                    'firstName' => 'John',
                    'lastName' => 'Smith',
                ],
                'address' => [
                    'street' => 'Shipping Street',
                    'houseNumber' => '20',
                    'zipcode' => '2000BB',
                    'city' => 'Rotterdam',
                    'country' => 'NL',
                ],
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-BILLINK-SHIPPING-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(199.99, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_pay_transaction_with_articles(): void
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
                        'Name' => 'billink',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-BILLINK-ARTICLES-001',
                'Currency' => 'EUR',
                'AmountDebit' => 375.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('billink')->pay([
            'amountDebit' => 375.00,
            'invoice' => 'INV-BILLINK-ARTICLES-001',
            'currency' => 'EUR',
            'articles' => [
                [
                    'identifier' => 'PROD-100',
                    'description' => 'Office Chair',
                    'quantity' => 2,
                    'price' => 150.00,
                    'vatPercentage' => 21,
                ],
                [
                    'identifier' => 'PROD-101',
                    'description' => 'Desk Lamp',
                    'quantity' => 1,
                    'price' => 75.00,
                    'vatPercentage' => 21,
                ],
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-BILLINK-ARTICLES-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(375.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_pays_remainder_amount(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $originalTxKey = 'ORIG_BILLINK_PARTIAL_TX_KEY';

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'PayRemainder successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'billink',
                        'Action' => 'PayRemainder',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-BILLINK-REMAINDER-001',
                'Currency' => 'EUR',
                'AmountDebit' => 100.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('billink')->payRemainder([
            'amountDebit' => 100.00,
            'invoice' => 'INV-BILLINK-REMAINDER-001',
            'originalTransactionKey' => $originalTxKey,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-BILLINK-REMAINDER-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(100.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_refunds_payment(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $originalTxKey = 'ORIG_BILLINK_PAY_TX_KEY';

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
                        'Name' => 'billink',
                        'Action' => 'Refund',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-BILLINK-REFUND-001',
                'Currency' => 'EUR',
                'AmountCredit' => 75.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('billink')->refund([
            'amountCredit' => 75.00,
            'invoice' => 'INV-BILLINK-REFUND-001',
            'originalTransactionKey' => $originalTxKey,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-BILLINK-REFUND-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(75.00, $response->getAmountCredit());
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
                        'Name' => 'billink',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-BILLINK-STATUS-001',
                'Currency' => 'EUR',
                'AmountDebit' => 10.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('billink')->pay([
            'amountDebit' => 10.00,
            'invoice' => 'INV-BILLINK-STATUS-001',
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
