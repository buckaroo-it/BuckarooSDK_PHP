<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\iDin\Models;

use Buckaroo\PaymentMethods\iDin\Models\Issuer;
use Tests\TestCase;

class IssuerTest extends TestCase
{
    /** @test */
    public function it_sets_issuer_property(): void
    {
        $issuer = new Issuer(['issuer' => 'ABNANL2A']);

        $this->assertSame('ABNANL2A', $issuer->issuer);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $issuer = new Issuer(['issuer' => 'SNSBNL2A']);

        $array = $issuer->toArray();

        $this->assertIsArray($array);
        $this->assertSame('SNSBNL2A', $array['issuer']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $issuer = new Issuer([]);

        $array = $issuer->toArray();
        $this->assertIsArray($array);
    }

    /**
     * @test
     * @dataProvider issuerCodesProvider
     */
    public function it_handles_various_issuer_codes(string $code): void
    {
        $issuer = new Issuer(['issuer' => $code]);
        $this->assertSame($code, $issuer->issuer);
    }

    public static function issuerCodesProvider(): array
    {
        return [
            ['ABNANL2A'],
            ['INGBNL2A'],
            ['RABONL2U'],
            ['SNSBNL2A'],
            ['ASNBNL21'],
            ['BUNQNL2A'],
        ];
    }
}
