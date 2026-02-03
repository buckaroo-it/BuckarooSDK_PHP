<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Billink\Models;

use Buckaroo\PaymentMethods\Billink\Models\Refund;
use Tests\TestCase;

class RefundTest extends TestCase
{
    /** @test */
    public function it_sets_refund_reason(): void
    {
        $refund = new Refund(['refundreason' => 'Customer requested return']);

        $this->assertSame('Customer requested return', $refund->refundreason);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $refund = new Refund(['refundreason' => 'Product damaged']);

        $array = $refund->toArray();

        $this->assertIsArray($array);
        $this->assertSame('Product damaged', $array['refundreason']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $refund = new Refund([]);

        $array = $refund->toArray();
        $this->assertIsArray($array);
    }

    /**
     * @test
     * @dataProvider refundReasonProvider
     */
    public function it_handles_various_refund_reasons(string $reason): void
    {
        $refund = new Refund(['refundreason' => $reason]);

        $this->assertSame($reason, $refund->refundreason);
    }

    public static function refundReasonProvider(): array
    {
        return [
            ['Customer requested return'],
            ['Product damaged'],
            ['Wrong item shipped'],
            ['Order cancelled'],
            ['Duplicate order'],
        ];
    }
}
