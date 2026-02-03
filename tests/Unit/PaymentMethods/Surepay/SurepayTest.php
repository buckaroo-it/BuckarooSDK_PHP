<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Surepay;

use Buckaroo\PaymentMethods\Surepay\Surepay;
use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

class SurepayTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->useMock();
    }

    /** @test */
    public function it_can_verify_bank_account(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/DataRequest*', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('surepay')
            ->verify([
                'bankAccount' => [
                    'iban' => 'NL13TEST0123456789',
                    'accountName' => 'John Doe',
                ],
            ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_returns_self_when_manually_enabled(): void
    {
        $payment = new Surepay($this->buckaroo->client(), 'surepay');
        $payment->manually(true);
        $payment->setPayload([
            'bankAccount' => [
                'iban' => 'NL13TEST0123456789',
                'accountName' => 'John Doe',
            ],
        ]);

        $result = $payment->verify();

        $this->assertInstanceOf(Surepay::class, $result);
    }
}
