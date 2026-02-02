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
class EPSTest extends FeatureTestCase
{
    /** @test */
    public function it_creates_an_eps_payment_with_redirect(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $redirectUrl = 'https://eps.at/redirect/' . $transactionKey;

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 791, 'Description' => 'Pending processing'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Redirecting to EPS'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => [
                    'Name' => 'Redirect',
                    'RedirectURL' => $redirectUrl,
                ],
                'Services' => [
                    [
                        'Name' => 'eps',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-EPS-001',
                'Currency' => 'EUR',
                'AmountDebit' => 50.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('eps')->pay([
            'amountDebit' => 50.00,
            'invoice' => 'INV-EPS-001',
        ]);

        $this->assertTrue($response->isPendingProcessing());
        $this->assertTrue($response->hasRedirect());
        $this->assertEquals($redirectUrl, $response->getRedirectUrl());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-EPS-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(50.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_an_eps_refund(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $originalTxKey = 'ORIG_EPS_TX_KEY';

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
                        'Name' => 'eps',
                        'Action' => 'Refund',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-REFUND-001',
                'Currency' => 'EUR',
                'AmountCredit' => 25.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('eps')->refund([
            'amountCredit' => 25.00,
            'invoice' => 'INV-REFUND-001',
            'originalTransactionKey' => $originalTxKey,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals('INV-REFUND-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(25.00, $response->getAmountCredit());
        $this->assertTrue($response->get('IsTest'));
        $this->assertEquals($transactionKey, $response->getTransactionKey());
    }

    /** @test */
    public function it_creates_an_eps_pay_remainder(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $redirectUrl = 'https://eps.at/redirect/' . $transactionKey;

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 791, 'Description' => 'Pending processing'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Redirecting to EPS'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => [
                    'Name' => 'Redirect',
                    'RedirectURL' => $redirectUrl,
                ],
                'Services' => [
                    [
                        'Name' => 'eps',
                        'Action' => 'PayRemainder',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-REMAINDER-001',
                'Currency' => 'EUR',
                'AmountDebit' => 30.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('eps')->payRemainder([
            'amountDebit' => 30.00,
            'invoice' => 'INV-REMAINDER-001',
        ]);

        $this->assertTrue($response->isPendingProcessing());
        $this->assertTrue($response->hasRedirect());
        $this->assertEquals('INV-REMAINDER-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(30.00, $response->getAmountDebit());
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
                        'Name' => 'eps',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-STATUS-001',
                'Currency' => 'EUR',
                'AmountDebit' => 10.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('eps')->pay([
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
