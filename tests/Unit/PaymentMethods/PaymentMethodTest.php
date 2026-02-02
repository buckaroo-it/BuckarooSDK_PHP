<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods;

use Buckaroo\PaymentMethods\CreditCard\CreditCard;
use Buckaroo\PaymentMethods\iDeal\iDeal;
use Buckaroo\PaymentMethods\PaymentInterface;
use Buckaroo\Transaction\Request\TransactionRequest;
use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->useMock();
    }

    /** @test */
    public function it_returns_payment_name(): void
    {
        $payment = new iDeal($this->buckaroo->client(), 'ideal');

        $this->assertSame('ideal', $payment->paymentName());
    }

    /** @test */
    public function it_returns_service_version(): void
    {
        $payment = new iDeal($this->buckaroo->client(), 'ideal');

        $this->assertIsInt($payment->serviceVersion());
    }

    /** @test */
    public function it_can_set_service_version(): void
    {
        $payment = new iDeal($this->buckaroo->client(), 'ideal');

        $result = $payment->setServiceVersion(5);

        $this->assertInstanceOf(PaymentInterface::class, $result);
        $this->assertSame(5, $payment->serviceVersion());
    }

    /** @test */
    public function it_returns_transaction_request(): void
    {
        $payment = new iDeal($this->buckaroo->client(), 'ideal');

        $request = $payment->request();

        $this->assertInstanceOf(TransactionRequest::class, $request);
    }

    /** @test */
    public function it_can_set_manually_mode(): void
    {
        $payment = new iDeal($this->buckaroo->client(), 'ideal');

        $result = $payment->manually(true);

        $this->assertSame($payment, $result);
    }

    /** @test */
    public function it_can_combine_payment(): void
    {
        // Create first payment (must implement Combinable)
        $firstPayment = new CreditCard($this->buckaroo->client(), 'creditcard');
        $firstPayment->manually(true);
        $firstPayment->setPayload([
            'amountDebit' => 10.00,
            'invoice' => 'COMBINED-001',
            'name' => 'visa',
        ]);
        $firstPayment->pay();

        // Create second payment and combine
        $secondPayment = new iDeal($this->buckaroo->client(), 'ideal');
        $result = $secondPayment->combinePayment($firstPayment);

        $this->assertSame($secondPayment, $result);
    }

    /** @test */
    public function it_combines_payment_service_lists(): void
    {
        // Create first payment with service list (must implement Combinable)
        $firstPayment = new CreditCard($this->buckaroo->client(), 'creditcard');
        $firstPayment->manually(true);
        $firstPayment->setPayload([
            'amountDebit' => 10.00,
            'invoice' => 'COMBINED-002',
            'name' => 'visa',
        ]);
        $firstPayment->pay();

        // Create second payment and combine
        $secondPayment = new iDeal($this->buckaroo->client(), 'ideal');
        $secondPayment->combinePayment($firstPayment);

        $services = $secondPayment->request()->getServices()->serviceList();

        $this->assertNotEmpty($services);
    }

    /** @test */
    public function it_can_set_payload(): void
    {
        $payment = new iDeal($this->buckaroo->client(), 'ideal');

        $result = $payment->setPayload([
            'amountDebit' => 15.00,
            'invoice' => 'SET-PAYLOAD-001',
        ]);

        $this->assertSame($payment, $result);
    }

    /** @test */
    public function it_returns_self_when_manually_enabled(): void
    {
        $payment = new iDeal($this->buckaroo->client(), 'ideal');
        $payment->manually(true);
        $payment->setPayload([
            'amountDebit' => 10.00,
            'invoice' => 'MANUAL-001',
            'issuer' => 'ABNANL2A',
        ]);

        $result = $payment->pay();

        $this->assertInstanceOf(iDeal::class, $result);
    }

    /** @test */
    public function it_returns_manually_with_null_parameter(): void
    {
        $payment = new iDeal($this->buckaroo->client(), 'ideal');

        $result = $payment->manually(null);

        $this->assertSame($payment, $result);
    }
}
