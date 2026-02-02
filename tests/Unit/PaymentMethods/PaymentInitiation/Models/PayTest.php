<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\PaymentInitiation\Models;

use Buckaroo\PaymentMethods\PaymentInitiation\Models\Pay;
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
    public function it_sets_country_code(): void
    {
        $pay = new Pay(['countryCode' => 'NL']);

        $this->assertSame('NL', $pay->countryCode);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $pay = new Pay([
            'issuer' => 'INGBNL2A',
            'countryCode' => 'BE',
        ]);

        $this->assertSame('INGBNL2A', $pay->issuer);
        $this->assertSame('BE', $pay->countryCode);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $pay = new Pay([
            'issuer' => 'RABONL2U',
            'countryCode' => 'DE',
        ]);

        $array = $pay->toArray();

        $this->assertIsArray($array);
        $this->assertSame('RABONL2U', $array['issuer']);
        $this->assertSame('DE', $array['countryCode']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $pay = new Pay([]);

        $array = $pay->toArray();
        $this->assertIsArray($array);
    }
}
