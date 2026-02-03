<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Wero;

use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

class WeroTest extends TestCase
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

        $response = $this->buckaroo->method('wero')->pay([
            'amountDebit' => 25.00,
            'invoice' => 'WERO-PAY-001',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_authorize(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('wero')->authorize([
            'amountDebit' => 50.00,
            'invoice' => 'WERO-AUTH-001',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_cancel_authorize(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('wero')->cancelAuthorize([
            'amountCredit' => 50.00,
            'invoice' => 'WERO-CANCEL-001',
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

        $response = $this->buckaroo->method('wero')->capture([
            'amountDebit' => 50.00,
            'invoice' => 'WERO-CAPTURE-001',
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

        $response = $this->buckaroo->method('wero')->refund([
            'amountCredit' => 25.00,
            'invoice' => 'WERO-REFUND-001',
            'originalTransactionKey' => 'ORIGINAL-TX-KEY',
        ]);

        $this->assertTrue($response->isSuccess());
    }
}
