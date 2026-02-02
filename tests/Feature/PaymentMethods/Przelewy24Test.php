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
class Przelewy24Test extends FeatureTestCase
{
    /** @test */
    public function it_creates_a_przelewy24_payment_with_redirect(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $redirectUrl = 'https://secure.przelewy24.pl/trnRequest/' . $transactionKey;

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 791, 'Description' => 'Pending processing'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Redirecting to Przelewy24'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => [
                    'Name' => 'Redirect',
                    'RedirectURL' => $redirectUrl,
                ],
                'Services' => [
                    [
                        'Name' => 'Przelewy24',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-P24-001',
                'Currency' => 'PLN',
                'AmountDebit' => 100.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('przelewy24')->pay([
            'amountDebit' => 100.00,
            'invoice' => 'INV-P24-001',
        ]);

        $this->assertTrue($response->isPendingProcessing());
        $this->assertTrue($response->hasRedirect());
        $this->assertEquals($redirectUrl, $response->getRedirectUrl());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-P24-001', $response->getInvoice());
        $this->assertEquals('PLN', $response->getCurrency());
        $this->assertEquals(100.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_payment_with_customer_details(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $redirectUrl = 'https://secure.przelewy24.pl/trnRequest/' . $transactionKey;

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 791, 'Description' => 'Pending processing'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Redirecting to Przelewy24'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => [
                    'Name' => 'Redirect',
                    'RedirectURL' => $redirectUrl,
                ],
                'Services' => [
                    [
                        'Name' => 'Przelewy24',
                        'Action' => 'Pay',
                        'Parameters' => [
                            ['Name' => 'CustomerFirstName', 'Value' => 'Jan'],
                            ['Name' => 'CustomerLastName', 'Value' => 'Kowalski'],
                        ],
                    ],
                ],
                'Invoice' => 'INV-P24-CUST-001',
                'Currency' => 'PLN',
                'AmountDebit' => 150.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('przelewy24')->pay([
            'amountDebit' => 150.00,
            'invoice' => 'INV-P24-CUST-001',
            'customer' => [
                'firstName' => 'Jan',
                'lastName' => 'Kowalski',
            ],
        ]);

        $this->assertTrue($response->isPendingProcessing());
        $this->assertTrue($response->hasRedirect());
        $this->assertEquals('INV-P24-CUST-001', $response->getInvoice());
        $this->assertEquals('PLN', $response->getCurrency());
        $this->assertEquals(150.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals($redirectUrl, $response->getRedirectUrl());
    }

    /** @test */
    public function it_creates_payment_with_email(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $redirectUrl = 'https://secure.przelewy24.pl/trnRequest/' . $transactionKey;

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 791, 'Description' => 'Pending processing'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Redirecting to Przelewy24'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => [
                    'Name' => 'Redirect',
                    'RedirectURL' => $redirectUrl,
                ],
                'Services' => [
                    [
                        'Name' => 'Przelewy24',
                        'Action' => 'Pay',
                        'Parameters' => [
                            ['Name' => 'CustomerEmail', 'Value' => 'jan.kowalski@example.pl'],
                        ],
                    ],
                ],
                'Invoice' => 'INV-P24-EMAIL-001',
                'Currency' => 'PLN',
                'AmountDebit' => 200.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('przelewy24')->pay([
            'amountDebit' => 200.00,
            'invoice' => 'INV-P24-EMAIL-001',
            'email' => 'jan.kowalski@example.pl',
        ]);

        $this->assertTrue($response->isPendingProcessing());
        $this->assertTrue($response->hasRedirect());
        $this->assertEquals('INV-P24-EMAIL-001', $response->getInvoice());
        $this->assertEquals('PLN', $response->getCurrency());
        $this->assertEquals(200.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals($redirectUrl, $response->getRedirectUrl());
    }

    /** @test */
    public function it_creates_a_przelewy24_refund(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $originalTxKey = 'ORIG_P24_TX_KEY';

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
                        'Name' => 'Przelewy24',
                        'Action' => 'Refund',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-REFUND-001',
                'Currency' => 'PLN',
                'AmountCredit' => 50.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('przelewy24')->refund([
            'amountCredit' => 50.00,
            'invoice' => 'INV-REFUND-001',
            'originalTransactionKey' => $originalTxKey,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals('INV-REFUND-001', $response->getInvoice());
        $this->assertEquals('PLN', $response->getCurrency());
        $this->assertEquals(50.00, $response->getAmountCredit());
        $this->assertTrue($response->get('IsTest'));
        $this->assertEquals($transactionKey, $response->getTransactionKey());
    }

    /** @test */
    public function it_creates_a_przelewy24_pay_remainder(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $redirectUrl = 'https://secure.przelewy24.pl/trnRequest/' . $transactionKey;

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 791, 'Description' => 'Pending processing'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Redirecting to Przelewy24'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => [
                    'Name' => 'Redirect',
                    'RedirectURL' => $redirectUrl,
                ],
                'Services' => [
                    [
                        'Name' => 'Przelewy24',
                        'Action' => 'PayRemainder',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-REMAINDER-001',
                'Currency' => 'PLN',
                'AmountDebit' => 75.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('przelewy24')->payRemainder([
            'amountDebit' => 75.00,
            'invoice' => 'INV-REMAINDER-001',
        ]);

        $this->assertTrue($response->isPendingProcessing());
        $this->assertTrue($response->hasRedirect());
        $this->assertEquals('INV-REMAINDER-001', $response->getInvoice());
        $this->assertEquals('PLN', $response->getCurrency());
        $this->assertEquals(75.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals($redirectUrl, $response->getRedirectUrl());
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
                        'Name' => 'Przelewy24',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-STATUS-001',
                'Currency' => 'PLN',
                'AmountDebit' => 10.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('przelewy24')->pay([
            'amountDebit' => 10.00,
            'invoice' => 'INV-STATUS-001',
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
