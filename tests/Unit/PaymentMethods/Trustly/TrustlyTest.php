<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Trustly;

use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

class TrustlyTest extends TestCase
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

        $response = $this->buckaroo->method('trustly')->pay([
            'amountDebit' => 25.00,
            'invoice' => 'TRUSTLY-PAY-001',
            'country' => 'NL',
            'customer' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_pay_remainder(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('trustly')->payRemainder([
            'amountDebit' => 10.00,
            'invoice' => 'TRUSTLY-REMAINDER-001',
            'originalTransactionKey' => 'ORIGINAL-TX-KEY',
            'country' => 'NL',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_refund(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('trustly')->refund([
            'amountCredit' => 25.00,
            'invoice' => 'TRUSTLY-REFUND-001',
            'originalTransactionKey' => 'ORIGINAL-TX-KEY',
        ]);

        $this->assertTrue($response->isSuccess());
    }
}
