<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\KnakenPay\Models;

use Buckaroo\PaymentMethods\KnakenPay\Models\Pay;
use Tests\TestCase;

class PayTest extends TestCase
{
    /** @test */
    public function it_sets_issuer(): void
    {
        $pay = new Pay(['issuer' => 'KNAKEN']);

        $this->assertSame('KNAKEN', $pay->issuer);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $pay = new Pay(['issuer' => 'ISSUER_CODE']);

        $array = $pay->toArray();

        $this->assertIsArray($array);
        $this->assertSame('ISSUER_CODE', $array['issuer']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $pay = new Pay([]);

        $array = $pay->toArray();
        $this->assertIsArray($array);
    }
}
