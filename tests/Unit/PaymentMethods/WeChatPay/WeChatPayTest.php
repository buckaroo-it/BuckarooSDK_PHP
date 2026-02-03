<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\WeChatPay;

use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

class WeChatPayTest extends TestCase
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

        $response = $this->buckaroo->method('wechatpay')->pay([
            'amountDebit' => 20.00,
            'invoice' => 'WECHAT-PAY-001',
            'locale' => 'en-US',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_refund(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('wechatpay')->refund([
            'amountCredit' => 20.00,
            'invoice' => 'WECHAT-REFUND-001',
            'originalTransactionKey' => 'ORIGINAL-TX-KEY',
        ]);

        $this->assertTrue($response->isSuccess());
    }
}
