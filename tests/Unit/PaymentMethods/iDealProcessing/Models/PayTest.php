<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\iDealProcessing\Models;

use Buckaroo\PaymentMethods\iDealProcessing\Models\Pay;
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
    public function it_converts_to_array(): void
    {
        $pay = new Pay(['issuer' => 'INGBNL2A']);

        $array = $pay->toArray();

        $this->assertIsArray($array);
        $this->assertSame('INGBNL2A', $array['issuer']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $pay = new Pay([]);

        $array = $pay->toArray();
        $this->assertIsArray($array);
    }

    /**
     * @test
     * @dataProvider issuerProvider
     */
    public function it_handles_various_issuers(string $issuer): void
    {
        $pay = new Pay(['issuer' => $issuer]);

        $this->assertSame($issuer, $pay->issuer);
    }

    public static function issuerProvider(): array
    {
        return [
            ['ABNANL2A'],
            ['INGBNL2A'],
            ['RABONL2U'],
            ['SNSBNL2A'],
            ['ASNBNL21'],
        ];
    }
}
