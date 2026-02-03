<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\GiftCard\Models;

use Buckaroo\PaymentMethods\GiftCard\Models\Refund;
use Tests\TestCase;

class RefundTest extends TestCase
{
    /** @test */
    public function it_sets_email(): void
    {
        $refund = new Refund(['email' => 'customer@example.com']);

        $this->assertSame('customer@example.com', $refund->email);
    }

    /** @test */
    public function it_sets_lastname(): void
    {
        $refund = new Refund(['lastname' => 'Doe']);

        $this->assertSame('Doe', $refund->lastname);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $refund = new Refund([
            'email' => 'john.doe@example.com',
            'lastname' => 'Doe',
        ]);

        $this->assertSame('john.doe@example.com', $refund->email);
        $this->assertSame('Doe', $refund->lastname);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $refund = new Refund([
            'email' => 'test@test.com',
            'lastname' => 'Smith',
        ]);

        $array = $refund->toArray();

        $this->assertIsArray($array);
        $this->assertSame('test@test.com', $array['email']);
        $this->assertSame('Smith', $array['lastname']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $refund = new Refund([]);

        $array = $refund->toArray();
        $this->assertIsArray($array);
    }
}
