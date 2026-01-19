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
class In3Test extends TestCase
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
                        'Name' => 'in3',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-IN3-001',
                'Currency' => 'EUR',
                'AmountDebit' => 250.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('in3')->pay([
            'amountDebit' => 250.00,
            'invoice' => 'INV-IN3-001',
            'currency' => 'EUR',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-IN3-001', $response->getInvoice());
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
                        'Name' => 'in3',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-IN3-BILLING-001',
                'Currency' => 'EUR',
                'AmountDebit' => 180.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('in3')->pay([
            'amountDebit' => 180.00,
            'invoice' => 'INV-IN3-BILLING-001',
            'currency' => 'EUR',
            'billing' => [
                'recipient' => [
                    'category' => 'B2C',
                    'firstName' => 'Jane',
                    'lastName' => 'Doe',
                    'birthDate' => '1985-05-15',
                ],
                'address' => [
                    'street' => 'Hoofdstraat',
                    'houseNumber' => '123',
                    'zipcode' => '1234AB',
                    'city' => 'Amsterdam',
                    'country' => 'NL',
                ],
                'phone' => [
                    'mobile' => '0612345678',
                ],
                'email' => 'jane.doe@example.com',
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-IN3-BILLING-001', $response->getInvoice());
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
                        'Name' => 'in3',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-IN3-ARTICLES-001',
                'Currency' => 'EUR',
                'AmountDebit' => 299.99,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('in3')->pay([
            'amountDebit' => 299.99,
            'invoice' => 'INV-IN3-ARTICLES-001',
            'currency' => 'EUR',
            'articles' => [
                [
                    'identifier' => 'PROD-100',
                    'description' => 'Laptop Computer',
                    'quantity' => 1,
                    'price' => 249.99,
                    'vatPercentage' => 21,
                ],
                [
                    'identifier' => 'PROD-101',
                    'description' => 'Mouse',
                    'quantity' => 1,
                    'price' => 50.00,
                    'vatPercentage' => 21,
                ],
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-IN3-ARTICLES-001', $response->getInvoice());
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
                        'Name' => 'in3',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-IN3-SHIPPING-001',
                'Currency' => 'EUR',
                'AmountDebit' => 199.99,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('in3')->pay([
            'amountDebit' => 199.99,
            'invoice' => 'INV-IN3-SHIPPING-001',
            'currency' => 'EUR',
            'billing' => [
                'recipient' => [
                    'category' => 'B2C',
                    'firstName' => 'John',
                    'lastName' => 'Smith',
                ],
                'address' => [
                    'street' => 'Billing Street',
                    'houseNumber' => '10',
                    'zipcode' => '1000AA',
                    'city' => 'Amsterdam',
                    'country' => 'NL',
                ],
                'email' => 'john.smith@example.com',
            ],
            'shipping' => [
                'recipient' => [
                    'category' => 'B2C',
                    'firstName' => 'Jane',
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
        $this->assertEquals('INV-IN3-SHIPPING-001', $response->getInvoice());
    }

    /** @test */
    public function it_pays_remainder_amount(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $originalTxKey = 'ORIG_IN3_PARTIAL_TX_KEY';

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
                        'Name' => 'in3',
                        'Action' => 'PayRemainder',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-IN3-REMAINDER-001',
                'Currency' => 'EUR',
                'AmountDebit' => 75.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('in3')->payRemainder([
            'amountDebit' => 75.00,
            'invoice' => 'INV-IN3-REMAINDER-001',
            'originalTransactionKey' => $originalTxKey,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-IN3-REMAINDER-001', $response->getInvoice());
    }

    /** @test */
    public function it_refunds_payment(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $originalTxKey = 'ORIG_IN3_PAY_TX_KEY';

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
                        'Name' => 'in3',
                        'Action' => 'Refund',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-IN3-REFUND-001',
                'Currency' => 'EUR',
                'AmountCredit' => 100.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('in3')->refund([
            'amountCredit' => 100.00,
            'invoice' => 'INV-IN3-REFUND-001',
            'originalTransactionKey' => $originalTxKey,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-IN3-REFUND-001', $response->getInvoice());
    }

    /** @test */
    public function it_refunds_payment_with_articles(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $originalTxKey = 'ORIG_IN3_PAY_TX_KEY';

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
                        'Name' => 'in3',
                        'Action' => 'Refund',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-IN3-REFUND-ARTICLES-001',
                'Currency' => 'EUR',
                'AmountCredit' => 50.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('in3')->refund([
            'amountCredit' => 50.00,
            'invoice' => 'INV-IN3-REFUND-ARTICLES-001',
            'originalTransactionKey' => $originalTxKey,
            'articles' => [
                [
                    'identifier' => 'PROD-101',
                    'description' => 'Mouse',
                    'quantity' => 1,
                    'price' => 50.00,
                    'vatPercentage' => 21,
                ],
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-IN3-REFUND-ARTICLES-001', $response->getInvoice());
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
                        'Name' => 'in3',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-IN3-STATUS-001',
                'Currency' => 'EUR',
                'AmountDebit' => 10.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('in3')->pay([
            'amountDebit' => 10.00,
            'invoice' => 'INV-IN3-STATUS-001',
            'currency' => 'EUR',
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
            'waiting_on_user_input' => [790, 'isWaitingOnUserInput'],
            'pending_processing' => [791, 'isPendingProcessing'],
            'cancelled' => [890, 'isCanceled'],
        ];
    }
}
