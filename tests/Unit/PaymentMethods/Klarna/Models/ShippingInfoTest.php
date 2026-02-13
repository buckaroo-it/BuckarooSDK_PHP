<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Klarna\Models;

use Buckaroo\Models\ShippingInfo as BaseShippingInfo;
use Buckaroo\PaymentMethods\Klarna\Models\ShippingInfo;
use Tests\TestCase;

class ShippingInfoTest extends TestCase
{
    /** @test */
    public function it_extends_base_shipping_info_model(): void
    {
        $shippingInfo = new ShippingInfo('ShippingInfo', []);

        $this->assertInstanceOf(BaseShippingInfo::class, $shippingInfo);
    }

    /** @test */
    public function it_sets_company(): void
    {
        $shippingInfo = new ShippingInfo('ShippingInfo', ['company' => 'DHL Express']);

        $this->assertSame('DHL Express', $shippingInfo->company);
    }

    /** @test */
    public function it_sets_tracking_number(): void
    {
        $shippingInfo = new ShippingInfo('ShippingInfo', ['trackingNumber' => 'TRACK-123456']);

        $this->assertSame('TRACK-123456', $shippingInfo->trackingNumber);
    }

    /** @test */
    public function it_sets_shipping_method(): void
    {
        $shippingInfo = new ShippingInfo('ShippingInfo', ['shippingMethod' => 'Next Day Delivery']);

        $this->assertSame('Next Day Delivery', $shippingInfo->shippingMethod);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $shippingInfo = new ShippingInfo('ShippingInfo', [
            'company' => 'FedEx',
            'trackingNumber' => 'FX987654321',
            'shippingMethod' => 'Express Overnight',
        ]);

        $this->assertSame('FedEx', $shippingInfo->company);
        $this->assertSame('FX987654321', $shippingInfo->trackingNumber);
        $this->assertSame('Express Overnight', $shippingInfo->shippingMethod);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $shippingInfo = new ShippingInfo('ShippingInfo', [
            'company' => 'UPS',
            'trackingNumber' => 'UPS12345',
            'shippingMethod' => 'Ground',
        ]);

        $array = $shippingInfo->toArray();

        $this->assertIsArray($array);
        $this->assertSame('UPS', $array['company']);
        $this->assertSame('UPS12345', $array['trackingNumber']);
        $this->assertSame('Ground', $array['shippingMethod']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $shippingInfo = new ShippingInfo('ShippingInfo', []);

        $array = $shippingInfo->toArray();
        $this->assertIsArray($array);
    }

    /** @test */
    public function it_handles_null_company(): void
    {
        $shippingInfo = new ShippingInfo('ShippingInfo', ['company' => null]);

        $this->assertNull($shippingInfo->company);
    }

    /** @test */
    public function it_handles_null_tracking_number(): void
    {
        $shippingInfo = new ShippingInfo('ShippingInfo', ['trackingNumber' => null]);

        $this->assertNull($shippingInfo->trackingNumber);
    }

    /** @test */
    public function it_handles_null_shipping_method(): void
    {
        $shippingInfo = new ShippingInfo('ShippingInfo', ['shippingMethod' => null]);

        $this->assertNull($shippingInfo->shippingMethod);
    }

    /** @test */
    public function it_handles_partial_data(): void
    {
        $shippingInfo = new ShippingInfo('ShippingInfo', [
            'company' => 'PostNL',
        ]);

        $this->assertSame('PostNL', $shippingInfo->company);
        $this->assertNull($shippingInfo->trackingNumber);
        $this->assertNull($shippingInfo->shippingMethod);
    }
}
