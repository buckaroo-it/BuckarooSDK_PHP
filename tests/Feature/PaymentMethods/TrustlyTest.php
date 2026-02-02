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
class TrustlyTest extends FeatureTestCase
{
    /** @test */
    public function it_creates_a_trustly_payment_with_redirect(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $redirectUrl = 'https://trustly.com/redirect/' . $transactionKey;

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 791, 'Description' => 'Pending processing'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Redirecting to Trustly'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => [
                    'Name' => 'Redirect',
                    'RedirectURL' => $redirectUrl,
                ],
                'Services' => [
                    [
                        'Name' => 'Trustly',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-TRUSTLY-001',
                'Currency' => 'EUR',
                'AmountDebit' => 45.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('trustly')->pay([
            'amountDebit' => 45.00,
            'invoice' => 'INV-TRUSTLY-001',
        ]);

        $this->assertTrue($response->isPendingProcessing());
        $this->assertTrue($response->hasRedirect());
        $this->assertEquals($redirectUrl, $response->getRedirectUrl());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-TRUSTLY-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(45.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_a_trustly_pay_remainder(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $redirectUrl = 'https://trustly.com/redirect/' . $transactionKey;

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 791, 'Description' => 'Pending processing'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Redirecting to Trustly'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => [
                    'Name' => 'Redirect',
                    'RedirectURL' => $redirectUrl,
                ],
                'Services' => [
                    [
                        'Name' => 'Trustly',
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

        $response = $this->buckaroo->method('trustly')->payRemainder([
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
                        'Name' => 'Trustly',
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

        $response = $this->buckaroo->method('trustly')->pay([
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
