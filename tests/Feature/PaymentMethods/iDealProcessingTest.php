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
class iDealProcessingTest extends TestCase
{
    /** @test */
    public function it_creates_ideal_processing_payment_with_redirect(): void
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
                        'Name' => 'idealprocessing',
                        'Action' => 'Pay',
                        'Parameters' => [
                            ['Name' => 'consumerIssuer', 'Value' => 'RABONL2U'],
                        ],
                    ]
                ],
                'Invoice' => 'INV-IDPRO-001',
                'Currency' => 'EUR',
                'AmountDebit' => 85.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('idealprocessing')->pay([
            'amountDebit' => 85.00,
            'invoice' => 'INV-IDPRO-001',
            'issuer' => 'RABONL2U',
        ]);

        $this->assertTrue($response->isPendingProcessing());
        $this->assertTrue($response->hasRedirect());
        $this->assertEquals($redirectUrl, $response->getRedirectUrl());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-IDPRO-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(85.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_ideal_processing_pay_remainder(): void
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
                        'Name' => 'idealprocessing',
                        'Action' => 'PayRemainder',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-IDPRO-REMAINDER-001',
                'Currency' => 'EUR',
                'AmountDebit' => 40.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('idealprocessing')->payRemainder([
            'amountDebit' => 40.00,
            'invoice' => 'INV-IDPRO-REMAINDER-001',
            'issuer' => 'RABONL2U',
        ]);

        $this->assertTrue($response->isPendingProcessing());
        $this->assertTrue($response->hasRedirect());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-IDPRO-REMAINDER-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(40.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
        $this->assertEquals($redirectUrl, $response->getRedirectUrl());
    }

    /** @test */
    public function it_creates_ideal_processing_refund(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $originalTxKey = 'ORIG_IDPRO_TX_KEY';

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
                        'Name' => 'idealprocessing',
                        'Action' => 'Refund',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-IDPRO-REFUND-001',
                'Currency' => 'EUR',
                'AmountCredit' => 20.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('idealprocessing')->refund([
            'amountCredit' => 20.00,
            'invoice' => 'INV-IDPRO-REFUND-001',
            'originalTransactionKey' => $originalTxKey,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-IDPRO-REFUND-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(20.00, $response->getAmountCredit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_fetches_issuers(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('GET', '*/json/Transaction/Specification/idealprocessing*', [
                'Actions' => [
                    [
                        'RequestParameters' => [
                            [
                                'ListItemDescriptions' => [
                                    ['Value' => 'RABONL2U', 'Description' => 'Rabobank'],
                                    ['Value' => 'INGBNL2A', 'Description' => 'ING Bank'],
                                ],
                            ],
                        ],
                    ]
                ],
            ]),
        ]);

        $issuers = $this->buckaroo->method('idealprocessing')->issuers();

        $this->assertIsArray($issuers);
        $this->assertNotEmpty($issuers);
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
                        'Name' => 'idealprocessing',
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

        $response = $this->buckaroo->method('idealprocessing')->pay([
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
                        'Name' => 'idealprocessing',
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

        $response = $this->buckaroo->method('idealprocessing')->pay([
            'amountDebit' => 10.00,
            'invoice' => 'INV-ISSUER-001',
            'issuer' => $issuer,
        ]);

        $this->assertTrue($response->isPendingProcessing());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
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
