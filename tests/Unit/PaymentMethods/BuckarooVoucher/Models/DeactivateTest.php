<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\BuckarooVoucher\Models;

use Buckaroo\PaymentMethods\BuckarooVoucher\Models\Deactivate;
use Buckaroo\PaymentMethods\BuckarooVoucher\Models\Pay;
use Tests\TestCase;

class DeactivateTest extends TestCase
{
    /** @test */
    public function it_extends_pay_model(): void
    {
        $deactivate = new Deactivate([]);

        $this->assertInstanceOf(Pay::class, $deactivate);
    }

    /** @test */
    public function it_sets_vouchercode(): void
    {
        $deactivate = new Deactivate(['vouchercode' => 'DEACTIVATE-001']);

        $this->assertSame('DEACTIVATE-001', $deactivate->vouchercode);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $deactivate = new Deactivate(['vouchercode' => 'DEACTIVATE-456']);

        $array = $deactivate->toArray();

        $this->assertIsArray($array);
        $this->assertSame('DEACTIVATE-456', $array['vouchercode']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $deactivate = new Deactivate([]);

        $array = $deactivate->toArray();
        $this->assertIsArray($array);
    }
}
