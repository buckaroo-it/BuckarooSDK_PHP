<?php

declare(strict_types=1);

namespace Tests\Feature\PaymentMethods;

use Tests\FeatureTestCase;
use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;

/**
 * Tests for NoServiceSpecifiedPayment - used for transactions without specifying a service
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NoServiceSpecifiedPaymentTest extends FeatureTestCase
{
    /** @test */
    public function it_creates_a_payment_without_service(): void
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
                'Services' => [],
                'Invoice' => 'INV-NOSERVICE-001',
                'Currency' => 'EUR',
                'AmountDebit' => 50.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('noservice')->pay([
            'amountDebit' => 50.00,
            'invoice' => 'INV-NOSERVICE-001',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-NOSERVICE-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(50.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_a_refund_without_service(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $originalTxKey = 'ORIG_NOSERVICE_TX_KEY';

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Refund successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [],
                'Invoice' => 'INV-NOSERVICE-REFUND-001',
                'Currency' => 'EUR',
                'AmountCredit' => 25.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('noservice')->refund([
            'amountCredit' => 25.00,
            'invoice' => 'INV-NOSERVICE-REFUND-001',
            'originalTransactionKey' => $originalTxKey,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-NOSERVICE-REFUND-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(25.00, $response->getAmountCredit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_a_pay_remainder_without_service(): void
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
                'Services' => [],
                'Invoice' => 'INV-NOSERVICE-REMAINDER-001',
                'Currency' => 'EUR',
                'AmountDebit' => 30.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('noservice')->payRemainder([
            'amountDebit' => 30.00,
            'invoice' => 'INV-NOSERVICE-REMAINDER-001',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-NOSERVICE-REMAINDER-001', $response->getInvoice());
        $this->assertEquals(30.00, $response->getAmountDebit());
    }

    /** @test */
    public function it_handles_redirect_response(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $redirectUrl = 'https://checkout.buckaroo.nl/html/redirect/' . $transactionKey;

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 791, 'Description' => 'Pending input'],
                    'SubCode' => ['Code' => 'S002', 'Description' => 'Waiting for user'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => [
                    'Name' => 'Redirect',
                    'RedirectURL' => $redirectUrl,
                ],
                'Services' => [],
                'Invoice' => 'INV-NOSERVICE-REDIRECT',
                'Currency' => 'EUR',
                'AmountDebit' => 75.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('noservice')->pay([
            'amountDebit' => 75.00,
            'invoice' => 'INV-NOSERVICE-REDIRECT',
        ]);

        $this->assertTrue($response->isPendingProcessing());
        $this->assertTrue($response->hasRedirect());
        $this->assertEquals($redirectUrl, $response->getRedirectUrl());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
    }

    /** @test */
    public function it_handles_custom_parameters(): void
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
                'Services' => [],
                'CustomParameters' => [
                    ['Name' => 'custom_param', 'Value' => 'custom_value'],
                ],
                'Invoice' => 'INV-NOSERVICE-CUSTOM',
                'Currency' => 'EUR',
                'AmountDebit' => 100.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('noservice')->pay([
            'amountDebit' => 100.00,
            'invoice' => 'INV-NOSERVICE-CUSTOM',
            'customParameters' => [
                'custom_param' => 'custom_value',
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
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
                'Services' => [],
                'Invoice' => 'INV-NOSERVICE-STATUS',
                'Currency' => 'EUR',
                'AmountDebit' => 10.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('noservice')->pay([
            'amountDebit' => 10.00,
            'invoice' => 'INV-NOSERVICE-STATUS',
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
            'cancelled' => [890, 'isCanceled'],
        ];
    }
}
