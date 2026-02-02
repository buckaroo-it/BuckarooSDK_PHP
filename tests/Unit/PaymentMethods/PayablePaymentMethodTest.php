<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods;

use Buckaroo\PaymentMethods\CreditCard\CreditCard;
use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

class PayablePaymentMethodTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->useMock();
    }

    /** @test */
    public function it_can_call_pay(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('creditcard')
            ->pay([
                'amountDebit' => 10.00,
                'invoice' => 'TEST-PAY-001',
                'name' => 'visa',
            ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_call_pay_remainder(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('creditcard')
            ->payRemainder([
                'amountDebit' => 5.00,
                'invoice' => 'TEST-REMAINDER-001',
                'name' => 'visa',
                'originalTransactionKey' => 'ORIGINAL-TX-KEY',
            ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_call_refund(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('creditcard')
            ->refund([
                'amountCredit' => 10.00,
                'invoice' => 'TEST-REFUND-001',
                'name' => 'visa',
                'originalTransactionKey' => 'ORIGINAL-TX-KEY',
            ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_returns_self_when_manually_enabled_for_pay(): void
    {
        $payment = $this->buckaroo->method('creditcard');
        $payment->manually(true);

        $result = $payment->pay([
            'amountDebit' => 10.00,
            'invoice' => 'TEST-MANUAL-001',
            'name' => 'visa',
        ]);

        $this->assertInstanceOf(CreditCard::class, $result);
    }

    /** @test */
    public function it_returns_self_when_manually_enabled_for_pay_remainder(): void
    {
        $payment = $this->buckaroo->method('creditcard');
        $payment->manually(true);

        $result = $payment->payRemainder([
            'amountDebit' => 5.00,
            'invoice' => 'TEST-MANUAL-002',
            'name' => 'visa',
        ]);

        $this->assertInstanceOf(CreditCard::class, $result);
    }

    /** @test */
    public function it_returns_self_when_manually_enabled_for_refund(): void
    {
        $payment = $this->buckaroo->method('creditcard');
        $payment->manually(true);

        $result = $payment->refund([
            'amountCredit' => 10.00,
            'invoice' => 'TEST-MANUAL-003',
            'name' => 'visa',
            'originalTransactionKey' => 'ORIGINAL-TX-KEY',
        ]);

        $this->assertInstanceOf(CreditCard::class, $result);
    }
}
