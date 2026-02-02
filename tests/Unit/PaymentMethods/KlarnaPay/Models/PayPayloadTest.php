<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\KlarnaPay\Models;

use Buckaroo\Models\Payload\PayPayload as BasePayPayload;
use Buckaroo\PaymentMethods\KlarnaPay\Models\PayPayload;
use Tests\TestCase;

class PayPayloadTest extends TestCase
{
    /** @test */
    public function it_extends_base_pay_payload(): void
    {
        $payload = new PayPayload([]);

        $this->assertInstanceOf(BasePayPayload::class, $payload);
    }

    /** @test */
    public function it_sets_services_selectable_by_client(): void
    {
        $payload = new PayPayload(['servicesSelectableByClient' => 'klarna,ideal']);

        $this->assertSame('klarna,ideal', $payload->servicesSelectableByClient);
    }

    /** @test */
    public function it_sets_services_excluded_for_client(): void
    {
        $payload = new PayPayload(['servicesExcludedForClient' => 'bancontact,sofort']);

        $this->assertSame('bancontact,sofort', $payload->servicesExcludedForClient);
    }

    /** @test */
    public function it_sets_original_transaction_reference(): void
    {
        $payload = new PayPayload(['originalTransactionReference' => 'TX-REF-12345']);

        $this->assertSame('TX-REF-12345', $payload->originalTransactionReference);
    }

    /** @test */
    public function it_sets_amount_debit_from_parent(): void
    {
        $payload = new PayPayload(['amountDebit' => 199.99]);

        $this->assertSame(199.99, $payload->amountDebit);
    }

    /** @test */
    public function it_sets_order_from_parent(): void
    {
        $payload = new PayPayload(['order' => 'KLARNA-ORDER-001']);

        $this->assertSame('KLARNA-ORDER-001', $payload->order);
    }

    /** @test */
    public function it_generates_default_order_when_not_provided(): void
    {
        $payload = new PayPayload([]);

        $this->assertStringStartsWith('ORDER_NO_', $payload->order);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $payload = new PayPayload([
            'amountDebit' => 250.00,
            'order' => 'KLARNA-FULL-ORDER',
            'servicesSelectableByClient' => 'klarna',
            'servicesExcludedForClient' => 'ideal',
            'originalTransactionReference' => 'ORIG-TX-999',
        ]);

        $this->assertSame(250.00, $payload->amountDebit);
        $this->assertSame('KLARNA-FULL-ORDER', $payload->order);
        $this->assertSame('klarna', $payload->servicesSelectableByClient);
        $this->assertSame('ideal', $payload->servicesExcludedForClient);
        $this->assertSame('ORIG-TX-999', $payload->originalTransactionReference);
    }

    /** @test */
    public function it_handles_null_payload(): void
    {
        $payload = new PayPayload(null);

        $this->assertInstanceOf(PayPayload::class, $payload);
        $this->assertStringStartsWith('ORDER_NO_', $payload->order);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $payload = new PayPayload([]);

        $this->assertInstanceOf(PayPayload::class, $payload);
    }

    /** @test */
    public function it_handles_empty_string_values(): void
    {
        $payload = new PayPayload([
            'servicesSelectableByClient' => '',
            'servicesExcludedForClient' => '',
            'originalTransactionReference' => '',
        ]);

        $this->assertSame('', $payload->servicesSelectableByClient);
        $this->assertSame('', $payload->servicesExcludedForClient);
        $this->assertSame('', $payload->originalTransactionReference);
    }
}
