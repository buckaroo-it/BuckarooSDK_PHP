<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Bancontact;

use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

/**
 */
class BancontactTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->useMock();
    }

    private function mockSuccessResponse(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);
    }

    /** @test */
    public function it_can_pay(): void
    {
        $this->mockSuccessResponse();

        $response = $this->buckaroo->method('bancontact')->pay([
            'amountDebit' => 10.00,
            'invoice' => 'TEST-PAY',
            'saveToken' => true,
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_pay_encrypted(): void
    {
        $this->mockSuccessResponse();

        $response = $this->buckaroo->method('bancontact')->payEncrypted([
            'amountDebit' => 10.00,
            'invoice' => 'TEST-PAY-ENCRYPTED',
            'encryptedCardData' => 'encrypted-data-here',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_pay_recurring(): void
    {
        $this->mockSuccessResponse();

        $response = $this->buckaroo->method('bancontact')->payRecurring([
            'amountDebit' => 10.00,
            'invoice' => 'TEST-PAY-RECURRING',
            'originalTransactionKey' => 'original-tx-key',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_pay_one_click(): void
    {
        $this->mockSuccessResponse();

        $response = $this->buckaroo->method('bancontact')->payOneClick([
            'amountDebit' => 10.00,
            'invoice' => 'TEST-PAY-ONE-CLICK',
            'originalTransactionKey' => 'original-tx-key',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_authenticate_deprecated(): void
    {
        $this->mockSuccessResponse();

        $response = $this->buckaroo->method('bancontact')->authenticate([
            'amountDebit' => 10.00,
            'invoice' => 'TEST-AUTHENTICATE',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_authorize(): void
    {
        $this->mockSuccessResponse();

        $response = $this->buckaroo->method('bancontact')->authorize([
            'amountDebit' => 10.00,
            'invoice' => 'TEST-AUTHORIZE',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_capture(): void
    {
        $this->mockSuccessResponse();

        $response = $this->buckaroo->method('bancontact')->capture([
            'amountDebit' => 10.00,
            'invoice' => 'TEST-CAPTURE',
            'originalTransactionKey' => 'original-tx-key',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_cancel_authorize(): void
    {
        $this->mockSuccessResponse();

        $response = $this->buckaroo->method('bancontact')->cancelAuthorize([
            'amountDebit' => 10.00,
            'invoice' => 'TEST-CANCEL-AUTH',
            'originalTransactionKey' => 'original-tx-key',
        ]);

        $this->assertTrue($response->isSuccess());
    }
}
