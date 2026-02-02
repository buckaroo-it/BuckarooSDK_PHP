<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Afterpay;

use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

class AfterpayTest extends TestCase
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

        $response = $this->buckaroo->method('afterpay')->pay([
            'amountDebit' => 50.00,
            'invoice' => 'AFTERPAY-PAY-001',
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

        $response = $this->buckaroo->method('afterpay')->authorize([
            'amountDebit' => 75.00,
            'invoice' => 'AFTERPAY-AUTH-001',
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

        $response = $this->buckaroo->method('afterpay')->cancelAuthorize([
            'amountCredit' => 75.00,
            'invoice' => 'AFTERPAY-CANCEL-001',
            'originalTransactionKey' => 'ORIGINAL-TX-KEY',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_capture(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('afterpay')->capture([
            'amountDebit' => 75.00,
            'invoice' => 'AFTERPAY-CAPTURE-001',
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
    public function it_can_refund(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('afterpay')->refund([
            'amountCredit' => 50.00,
            'invoice' => 'AFTERPAY-REFUND-001',
            'originalTransactionKey' => 'ORIGINAL-TX-KEY',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_pay_remainder(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('afterpay')->payRemainder([
            'amountDebit' => 25.00,
            'invoice' => 'AFTERPAY-REMAINDER-001',
            'originalTransactionKey' => 'ORIGINAL-TX-KEY',
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
}
