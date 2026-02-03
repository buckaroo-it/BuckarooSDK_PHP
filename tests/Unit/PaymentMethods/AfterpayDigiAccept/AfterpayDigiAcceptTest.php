<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\AfterpayDigiAccept;

use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

class AfterpayDigiAcceptTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->useMock();
    }

    /** @test */
    public function it_can_pay(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('afterpaydigiaccept')->pay([
            'amountDebit' => 50.00,
            'invoice' => 'AFTERPAY-DIGI-PAY-001',
            'billing' => [
                'recipient' => [
                    'category' => 'Person',
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ],
            ],
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_authorize(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('afterpaydigiaccept')->authorize([
            'amountDebit' => 75.00,
            'invoice' => 'AFTERPAY-DIGI-AUTH-001',
            'billing' => [
                'recipient' => [
                    'category' => 'Person',
                    'firstName' => 'Jane',
                    'lastName' => 'Smith',
                ],
            ],
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_capture(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('afterpaydigiaccept')->capture([
            'amountDebit' => 75.00,
            'invoice' => 'AFTERPAY-DIGI-CAPTURE-001',
            'originalTransactionKey' => 'ORIGINAL-TX-KEY',
            'billing' => [
                'recipient' => [
                    'category' => 'Person',
                    'firstName' => 'Jane',
                    'lastName' => 'Smith',
                ],
            ],
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_cancel_authorize(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('afterpaydigiaccept')->cancelAuthorize([
            'amountCredit' => 75.00,
            'invoice' => 'AFTERPAY-DIGI-CANCEL-001',
            'originalTransactionKey' => 'ORIGINAL-TX-KEY',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_refund(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('afterpaydigiaccept')->refund([
            'amountCredit' => 50.00,
            'invoice' => 'AFTERPAY-DIGI-REFUND-001',
            'originalTransactionKey' => 'ORIGINAL-TX-KEY',
        ]);

        $this->assertTrue($response->isSuccess());
    }
}
