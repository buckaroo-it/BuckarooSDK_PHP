<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\BuckarooWallet\Models;

use Buckaroo\Models\Payload\Payload;
use Buckaroo\PaymentMethods\BuckarooWallet\Models\DepositReservePayload;
use Tests\TestCase;

class DepositReservePayloadTest extends TestCase
{
    /** @test */
    public function it_extends_payload_model(): void
    {
        $payload = new DepositReservePayload([]);

        $this->assertInstanceOf(Payload::class, $payload);
    }

    /** @test */
    public function it_sets_invoice(): void
    {
        $payload = new DepositReservePayload(['invoice' => 'INV-DEPOSIT-001']);

        $this->assertSame('INV-DEPOSIT-001', $payload->invoice);
    }

    /** @test */
    public function it_sets_amount_credit(): void
    {
        $payload = new DepositReservePayload(['amountCredit' => 150.00]);

        $this->assertSame(150.00, $payload->amountCredit);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $payload = new DepositReservePayload([
            'invoice' => 'INV-999',
            'amountCredit' => 250.50,
        ]);

        $this->assertSame('INV-999', $payload->invoice);
        $this->assertSame(250.50, $payload->amountCredit);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $payload = new DepositReservePayload([
            'invoice' => 'INV-TEST',
            'amountCredit' => 100.00,
        ]);

        $array = $payload->toArray();

        $this->assertIsArray($array);
        $this->assertSame('INV-TEST', $array['invoice']);
        $this->assertSame(100.00, $array['amountCredit']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $payload = new DepositReservePayload([]);

        $array = $payload->toArray();
        $this->assertIsArray($array);
    }
}
