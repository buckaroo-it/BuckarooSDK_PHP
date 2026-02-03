<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\GiftCard\Models;

use Buckaroo\PaymentMethods\GiftCard\Models\Pay;
use Tests\TestCase;

class PayTest extends TestCase
{
    /** @test */
    public function it_sets_fashion_cheque_card_number(): void
    {
        $pay = new Pay(['fashionChequeCardNumber' => '1234567890']);

        $this->assertSame('1234567890', $pay->fashionChequeCardNumber);
    }

    /** @test */
    public function it_sets_fashion_cheque_pin(): void
    {
        $pay = new Pay(['fashionChequePin' => '1234']);

        $this->assertSame('1234', $pay->fashionChequePin);
    }

    /** @test */
    public function it_sets_intersolve_cardnumber(): void
    {
        $pay = new Pay(['intersolveCardnumber' => '9876543210']);

        $this->assertSame('9876543210', $pay->intersolveCardnumber);
    }

    /** @test */
    public function it_sets_intersolve_pin(): void
    {
        $pay = new Pay(['intersolvePIN' => '5678']);

        $this->assertSame('5678', $pay->intersolvePIN);
    }

    /** @test */
    public function it_sets_tcs_cardnumber(): void
    {
        $pay = new Pay(['tcsCardnumber' => '1111222233334444']);

        $this->assertSame('1111222233334444', $pay->tcsCardnumber);
    }

    /** @test */
    public function it_sets_tcs_validation_code(): void
    {
        $pay = new Pay(['tcsValidationCode' => 'VALID123']);

        $this->assertSame('VALID123', $pay->tcsValidationCode);
    }

    /** @test */
    public function it_sets_last_name(): void
    {
        $pay = new Pay(['lastName' => 'Doe']);

        $this->assertSame('Doe', $pay->lastName);
    }

    /** @test */
    public function it_sets_email(): void
    {
        $pay = new Pay(['email' => 'test@example.com']);

        $this->assertSame('test@example.com', $pay->email);
    }

    /** @test */
    public function it_sets_card_number(): void
    {
        $pay = new Pay(['cardNumber' => '5555666677778888']);

        $this->assertSame('5555666677778888', $pay->cardNumber);
    }

    /** @test */
    public function it_sets_pin(): void
    {
        $pay = new Pay(['pin' => '9999']);

        $this->assertSame('9999', $pay->pin);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $pay = new Pay([
            'cardNumber' => '1234123412341234',
            'pin' => '0000',
            'lastName' => 'Smith',
            'email' => 'smith@example.com',
        ]);

        $this->assertSame('1234123412341234', $pay->cardNumber);
        $this->assertSame('0000', $pay->pin);
        $this->assertSame('Smith', $pay->lastName);
        $this->assertSame('smith@example.com', $pay->email);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $pay = new Pay([
            'cardNumber' => 'TEST-CARD',
            'pin' => '1111',
        ]);

        $array = $pay->toArray();

        $this->assertIsArray($array);
        $this->assertSame('TEST-CARD', $array['cardNumber']);
        $this->assertSame('1111', $array['pin']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $pay = new Pay([]);

        $array = $pay->toArray();
        $this->assertIsArray($array);
    }
}
