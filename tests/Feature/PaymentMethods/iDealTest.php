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
class iDealTest extends TestCase
{
    /** @test */
    public function it_creates_an_ideal_payment_with_redirect(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $redirectUrl = 'https://ideal.rabobank.nl/secure/login?trx=' . $transactionKey;

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 791, 'Description' => 'Pending processing'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Redirecting to bank'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => [
                    'Name' => 'Redirect',
                    'RedirectURL' => $redirectUrl,
                ],
                'Services' => [
                    [
                        'Name' => 'ideal',
                        'Action' => 'Pay',
                        'Parameters' => [
                            ['Name' => 'consumerIssuer', 'Value' => 'RABONL2U'],
                        ],
                    ]
                ],
                'Invoice' => 'INV-IDEAL-001',
                'Currency' => 'EUR',
                'AmountDebit' => 25.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('ideal')->pay([
            'amountDebit' => 25.00,
            'invoice' => 'INV-IDEAL-001',
            'issuer' => 'RABONL2U',
        ]);

        $this->assertTrue($response->isPendingProcessing());
        $this->assertTrue($response->hasRedirect());
        $this->assertEquals($redirectUrl, $response->getRedirectUrl());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-IDEAL-001', $response->getInvoice());
    }

    /** @test */
    public function it_creates_an_ideal_refund(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $originalTxKey = 'ORIG_IDEAL_TX_KEY';

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
                        'Name' => 'ideal',
                        'Action' => 'Refund',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-REFUND-001',
                'Currency' => 'EUR',
                'AmountCredit' => 10.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('ideal')->refund([
            'amountCredit' => 10.00,
            'invoice' => 'INV-REFUND-001',
            'originalTransactionKey' => $originalTxKey,
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_creates_an_ideal_instant_refund(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $originalTxKey = 'ORIG_IDEAL_TX_KEY';

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Instant refund successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'ideal',
                        'Action' => 'instantRefund',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-INSTANT-REFUND-001',
                'Currency' => 'EUR',
                'AmountCredit' => 15.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('ideal')->instantRefund([
            'amountCredit' => 15.00,
            'invoice' => 'INV-INSTANT-REFUND-001',
            'originalTransactionKey' => $originalTxKey,
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_creates_an_ideal_pay_remainder(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $redirectUrl = 'https://ideal.rabobank.nl/secure/login?trx=' . $transactionKey;

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 791, 'Description' => 'Pending processing'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Redirecting to bank'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => [
                    'Name' => 'Redirect',
                    'RedirectURL' => $redirectUrl,
                ],
                'Services' => [
                    [
                        'Name' => 'ideal',
                        'Action' => 'PayRemainder',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-REMAINDER-001',
                'Currency' => 'EUR',
                'AmountDebit' => 50.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('ideal')->payRemainder([
            'amountDebit' => 50.00,
            'invoice' => 'INV-REMAINDER-001',
            'issuer' => 'RABONL2U',
        ]);

        $this->assertTrue($response->isPendingProcessing());
        $this->assertTrue($response->hasRedirect());
    }

    /** @test */
    public function it_creates_fast_checkout_payment(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $redirectUrl = 'https://ideal.rabobank.nl/secure/login?trx=' . $transactionKey;

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 791, 'Description' => 'Pending processing'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Redirecting to bank'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => [
                    'Name' => 'Redirect',
                    'RedirectURL' => $redirectUrl,
                ],
                'Services' => [
                    [
                        'Name' => 'ideal',
                        'Action' => 'PayFastCheckout',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-FAST-001',
                'Currency' => 'EUR',
                'AmountDebit' => 35.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('ideal')->payFastCheckout([
            'amountDebit' => 35.00,
            'invoice' => 'INV-FAST-001',
            'issuer' => 'RABONL2U',
        ]);

        $this->assertTrue($response->isPendingProcessing());
        $this->assertTrue($response->hasRedirect());
    }

    /** @test */
    public function it_fetches_issuers(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('GET', '*/json/Transaction/Specification/ideal*', [
                'Services' => [
                    [
                        'Name' => 'ideal',
                        'Parameters' => [
                            ['Name' => 'issuer', 'ListItemDescription' => 'Rabobank', 'ListItemID' => 'RABONL2U'],
                        ],
                    ]
                ],
            ]),
        ]);

        $issuers = $this->buckaroo->method('ideal')->issuers();

        $this->assertIsArray($issuers);
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
                        'Name' => 'ideal',
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

        $response = $this->buckaroo->method('ideal')->pay([
            'amountDebit' => 10.00,
            'invoice' => 'INV-STATUS-001',
            'issuer' => 'RABONL2U',
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

    /**
     * @test
     * @dataProvider issuerProvider
     */
    public function it_works_with_different_issuers(string $issuer): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $redirectUrl = "https://ideal.bank.nl/secure/login?trx={$transactionKey}";

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 791, 'Description' => 'Pending processing'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Redirecting to bank'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => [
                    'Name' => 'Redirect',
                    'RedirectURL' => $redirectUrl,
                ],
                'Services' => [
                    [
                        'Name' => 'ideal',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-ISSUER-001',
                'Currency' => 'EUR',
                'AmountDebit' => 10.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('ideal')->pay([
            'amountDebit' => 10.00,
            'invoice' => 'INV-ISSUER-001',
            'issuer' => $issuer,
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    public static function issuerProvider(): array
    {
        return [
            ['RABONL2U'],
            ['INGBNL2A'],
            ['ABNANL2A'],
        ];
    }
}
