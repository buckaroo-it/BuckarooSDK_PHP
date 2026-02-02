<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\BuckarooVoucher\Models;

use Buckaroo\PaymentMethods\BuckarooVoucher\Models\GetBalance;
use Buckaroo\PaymentMethods\BuckarooVoucher\Models\Pay;
use Tests\TestCase;

class GetBalanceTest extends TestCase
{
    /** @test */
    public function it_extends_pay_model(): void
    {
        $getBalance = new GetBalance([]);

        $this->assertInstanceOf(Pay::class, $getBalance);
    }

    /** @test */
    public function it_sets_vouchercode(): void
    {
        $getBalance = new GetBalance(['vouchercode' => 'BALANCE-CHECK-001']);

        $this->assertSame('BALANCE-CHECK-001', $getBalance->vouchercode);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $getBalance = new GetBalance(['vouchercode' => 'BALANCE-456']);

        $array = $getBalance->toArray();

        $this->assertIsArray($array);
        $this->assertSame('BALANCE-456', $array['vouchercode']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $getBalance = new GetBalance([]);

        $array = $getBalance->toArray();
        $this->assertIsArray($array);
    }
}
