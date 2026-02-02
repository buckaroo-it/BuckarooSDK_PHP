<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\ApplePay\Models;

use Buckaroo\PaymentMethods\ApplePay\Models\Pay;
use Tests\TestCase;

class PayTest extends TestCase
{
    /** @test */
    public function it_sets_payment_data(): void
    {
        $pay = new Pay(['paymentData' => 'ENCRYPTED_PAYMENT_DATA']);

        $this->assertSame('ENCRYPTED_PAYMENT_DATA', $pay->paymentData);
    }

    /** @test */
    public function it_sets_customer_card_name(): void
    {
        $pay = new Pay(['customerCardName' => 'John Doe']);

        $this->assertSame('John Doe', $pay->customerCardName);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $pay = new Pay([
            'paymentData' => 'PAYMENT_DATA_123',
            'customerCardName' => 'Jane Smith',
        ]);

        $this->assertSame('PAYMENT_DATA_123', $pay->paymentData);
        $this->assertSame('Jane Smith', $pay->customerCardName);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $pay = new Pay([
            'paymentData' => 'DATA_456',
            'customerCardName' => 'Test User',
        ]);

        $array = $pay->toArray();

        $this->assertIsArray($array);
        $this->assertSame('DATA_456', $array['paymentData']);
        $this->assertSame('Test User', $array['customerCardName']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $pay = new Pay([]);

        $array = $pay->toArray();
        $this->assertIsArray($array);
    }
}
