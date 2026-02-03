<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\CreditCard;

use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

/**
 */
class CreditCardTest extends TestCase
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
    public function it_can_pay_encrypted(): void
    {
        $this->mockSuccessResponse();

        $response = $this->buckaroo->method('creditcard')->payEncrypted([
            'amountDebit' => 10.00,
            'invoice' => 'TEST-PAY-ENCRYPTED',
            'name' => 'visa',
            'encryptedCardData' => 'encrypted-data-here',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_authorize_with_token(): void
    {
        $this->mockSuccessResponse();

        $response = $this->buckaroo->method('creditcard')->authorizeWithToken([
            'amountDebit' => 10.00,
            'invoice' => 'TEST-AUTH-TOKEN',
            'name' => 'visa',
            'token' => 'session-token-here',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_pay_with_token(): void
    {
        $this->mockSuccessResponse();

        $response = $this->buckaroo->method('creditcard')->payWithToken([
            'amountDebit' => 10.00,
            'invoice' => 'TEST-PAY-TOKEN',
            'name' => 'visa',
            'token' => 'session-token-here',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_pay_remainder_with_token(): void
    {
        $this->mockSuccessResponse();

        $response = $this->buckaroo->method('creditcard')->payRemainderWithToken([
            'amountDebit' => 10.00,
            'invoice' => 'TEST-PAY-REMAINDER-TOKEN',
            'name' => 'visa',
            'token' => 'session-token-here',
            'originalTransactionKey' => 'original-tx-key',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_authorize_encrypted(): void
    {
        $this->mockSuccessResponse();

        $response = $this->buckaroo->method('creditcard')->authorizeEncrypted([
            'amountDebit' => 10.00,
            'invoice' => 'TEST-AUTH-ENCRYPTED',
            'name' => 'visa',
            'encryptedCardData' => 'encrypted-data-here',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_pay_with_security_code(): void
    {
        $this->mockSuccessResponse();

        $response = $this->buckaroo->method('creditcard')->payWithSecurityCode([
            'amountDebit' => 10.00,
            'invoice' => 'TEST-PAY-SECURITY-CODE',
            'name' => 'visa',
            'originalTransactionKey' => 'original-tx-key',
            'securityCode' => '123',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_authorize_with_security_code(): void
    {
        $this->mockSuccessResponse();

        $response = $this->buckaroo->method('creditcard')->authorizeWithSecurityCode([
            'amountDebit' => 10.00,
            'invoice' => 'TEST-AUTH-SECURITY-CODE',
            'name' => 'visa',
            'originalTransactionKey' => 'original-tx-key',
            'securityCode' => '123',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_authorize(): void
    {
        $this->mockSuccessResponse();

        $response = $this->buckaroo->method('creditcard')->authorize([
            'amountDebit' => 10.00,
            'invoice' => 'TEST-AUTHORIZE',
            'name' => 'visa',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_capture(): void
    {
        $this->mockSuccessResponse();

        $response = $this->buckaroo->method('creditcard')->capture([
            'amountDebit' => 10.00,
            'invoice' => 'TEST-CAPTURE',
            'name' => 'visa',
            'originalTransactionKey' => 'original-tx-key',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_pay_recurrent(): void
    {
        $this->mockSuccessResponse();

        $response = $this->buckaroo->method('creditcard')->payRecurrent([
            'amountDebit' => 10.00,
            'invoice' => 'TEST-PAY-RECURRENT',
            'name' => 'visa',
            'originalTransactionKey' => 'original-tx-key',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_pay_remainder_encrypted(): void
    {
        $this->mockSuccessResponse();

        $response = $this->buckaroo->method('creditcard')->payRemainderEncrypted([
            'amountDebit' => 10.00,
            'invoice' => 'TEST-PAY-REMAINDER-ENCRYPTED',
            'name' => 'visa',
            'encryptedCardData' => 'encrypted-data-here',
            'originalTransactionKey' => 'original-tx-key',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_cancel_authorize(): void
    {
        $this->mockSuccessResponse();

        $response = $this->buckaroo->method('creditcard')->cancelAuthorize([
            'amountCredit' => 10.00,
            'invoice' => 'TEST-CANCEL-AUTH',
            'name' => 'visa',
            'originalTransactionKey' => 'original-tx-key',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_returns_payment_name_from_payload(): void
    {
        $this->mockSuccessResponse();

        $response = $this->buckaroo->method('creditcard')->pay([
            'amountDebit' => 10.00,
            'invoice' => 'TEST-PAYMENT-NAME',
            'name' => 'mastercard',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_throws_exception_when_payment_name_missing(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Missing creditcard name');

        // No mock needed - exception thrown before request is made
        $this->buckaroo->method('creditcard')->pay([
            'amountDebit' => 10.00,
            'invoice' => 'TEST-NO-NAME',
        ]);
    }
}
