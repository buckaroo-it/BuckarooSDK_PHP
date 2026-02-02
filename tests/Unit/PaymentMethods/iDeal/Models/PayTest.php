<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\iDeal\Models;

use Buckaroo\PaymentMethods\iDeal\Models\Pay;
use Tests\TestCase;

class PayTest extends TestCase
{
    /** @test */
    public function it_sets_issuer(): void
    {
        $pay = new Pay(['issuer' => 'ABNANL2A']);

        $this->assertSame('ABNANL2A', $pay->issuer);
    }

    /** @test */
    public function it_allows_null_issuer(): void
    {
        $pay = new Pay(['issuer' => null]);

        $this->assertNull($pay->issuer);
    }

    /** @test */
    public function it_sets_shipping_cost(): void
    {
        $pay = new Pay(['shippingCost' => '5.95']);

        $this->assertSame('5.95', $pay->shippingCost);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $pay = new Pay([
            'issuer' => 'INGBNL2A',
            'shippingCost' => '10.00',
        ]);

        $this->assertSame('INGBNL2A', $pay->issuer);
        $this->assertSame('10.00', $pay->shippingCost);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $pay = new Pay([
            'issuer' => 'RABONL2U',
            'shippingCost' => '7.50',
        ]);

        $array = $pay->toArray();

        $this->assertIsArray($array);
        $this->assertSame('RABONL2U', $array['issuer']);
        $this->assertSame('7.50', $array['shippingCost']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $pay = new Pay([]);

        $array = $pay->toArray();
        $this->assertIsArray($array);
    }
}
