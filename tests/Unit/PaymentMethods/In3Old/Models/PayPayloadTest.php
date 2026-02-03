<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\In3Old\Models;

use Buckaroo\Models\ClientIP;
use Buckaroo\Models\Payload\PayPayload as BasePayPayload;
use Buckaroo\PaymentMethods\In3Old\Models\PayPayload;
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
    public function it_initializes_client_ip_in_constructor(): void
    {
        $payload = new PayPayload([]);

        $reflection = new \ReflectionClass($payload);
        $property = $reflection->getProperty('clientIP');
        $property->setAccessible(true);

        $this->assertInstanceOf(ClientIP::class, $property->getValue($payload));
    }

    /** @test */
    public function it_sets_amount_debit_from_parent(): void
    {
        $payload = new PayPayload(['amountDebit' => 99.95]);

        $this->assertSame(99.95, $payload->amountDebit);
    }

    /** @test */
    public function it_sets_order_from_parent(): void
    {
        $payload = new PayPayload(['order' => 'IN3-ORDER-001']);

        $this->assertSame('IN3-ORDER-001', $payload->order);
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
            'amountDebit' => 150.00,
            'order' => 'IN3-TEST-ORDER',
        ]);

        $this->assertSame(150.00, $payload->amountDebit);
        $this->assertSame('IN3-TEST-ORDER', $payload->order);

        $reflection = new \ReflectionClass($payload);
        $property = $reflection->getProperty('clientIP');
        $property->setAccessible(true);
        $this->assertInstanceOf(ClientIP::class, $property->getValue($payload));
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
}
