<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\BuckarooVoucher;

use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class BuckarooVoucherTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->useMock();
    }

    /** @test */
    public function it_can_pay_with_voucher(): void
    {
        $this->mockSuccessResponse();

        $response = $this->buckaroo->method('buckaroovoucher')->pay([
            'amountDebit' => 10.00,
            'invoice' => 'TEST-VOUCHER-PAY',
            'voucherCode' => 'VOUCHER123',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_pay_remainder_with_voucher(): void
    {
        $this->mockSuccessResponse();

        $response = $this->buckaroo->method('buckaroovoucher')->payRemainder([
            'amountDebit' => 5.00,
            'invoice' => 'TEST-VOUCHER-REMAINDER',
            'voucherCode' => 'VOUCHER123',
            'originalTransactionKey' => 'ORIGINAL-TX-KEY',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_get_balance(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/DataRequest/', TestHelpers::successResponse([
                'ServiceParameters' => [
                    ['Name' => 'Balance', 'Value' => '50.00'],
                ],
            ])),
        ]);

        $response = $this->buckaroo->method('buckaroovoucher')->getBalance([
            'voucherCode' => 'VOUCHER123',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_create_voucher(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/DataRequest/', TestHelpers::successResponse([
                'ServiceParameters' => [
                    ['Name' => 'VoucherCode', 'Value' => 'NEW-VOUCHER-123'],
                ],
            ])),
        ]);

        $response = $this->buckaroo->method('buckaroovoucher')->create([
            'groupGuid' => 'group-123',
            'usageType' => '1',
            'validFrom' => '2024-01-01',
            'validUntil' => '2025-12-31',
            'creationBalance' => 100.00,
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_deactivate_voucher(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/DataRequest/', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('buckaroovoucher')->deactivate([
            'voucherCode' => 'VOUCHER123',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    private function mockSuccessResponse(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);
    }
}
