<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Alipay\Models;

use Buckaroo\PaymentMethods\Alipay\Models\Pay;
use Tests\TestCase;

class PayTest extends TestCase
{
    /** @test */
    public function it_sets_use_mobile_view_true(): void
    {
        $pay = new Pay(['useMobileView' => true]);

        $this->assertTrue($pay->useMobileView);
    }

    /** @test */
    public function it_sets_use_mobile_view_false(): void
    {
        $pay = new Pay(['useMobileView' => false]);

        $this->assertFalse($pay->useMobileView);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $pay = new Pay(['useMobileView' => true]);

        $array = $pay->toArray();

        $this->assertIsArray($array);
        $this->assertTrue($array['useMobileView']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $pay = new Pay([]);

        $array = $pay->toArray();
        $this->assertIsArray($array);
    }
}
