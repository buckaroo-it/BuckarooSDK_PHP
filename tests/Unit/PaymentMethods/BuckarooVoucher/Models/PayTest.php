<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\BuckarooVoucher\Models;

use Buckaroo\PaymentMethods\BuckarooVoucher\Models\Pay;
use Tests\TestCase;

class PayTest extends TestCase
{
    /** @test */
    public function it_sets_vouchercode(): void
    {
        $pay = new Pay(['vouchercode' => 'VOUCHER-CODE-123']);

        $this->assertSame('VOUCHER-CODE-123', $pay->vouchercode);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $pay = new Pay(['vouchercode' => 'VOUCHER-456']);

        $array = $pay->toArray();

        $this->assertIsArray($array);
        $this->assertSame('VOUCHER-456', $array['vouchercode']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $pay = new Pay([]);

        $array = $pay->toArray();
        $this->assertIsArray($array);
    }
}
