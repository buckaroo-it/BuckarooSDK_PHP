<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\ApplePay\Models;

use Buckaroo\Models\Payload\PayPayload as BasePayPayload;
use Buckaroo\PaymentMethods\ApplePay\Models\PayPayload;
use Tests\TestCase;

class PayPayloadTest extends TestCase
{
    /** @test */
    public function it_extends_base_pay_payload(): void
    {
        $payload = new PayPayload([]);

        $this->assertInstanceOf(BasePayPayload::class, $payload);
    }

    /** @test */
    public function it_sets_continue_on_incomplete(): void
    {
        $payload = new PayPayload(['continueOnIncomplete' => 'RedirectToHTML']);

        $this->assertSame('RedirectToHTML', $payload->continueOnIncomplete);
    }

    /** @test */
    public function it_sets_services_selectable_by_client(): void
    {
        $payload = new PayPayload(['servicesSelectableByClient' => 'applepay,ideal']);

        $this->assertSame('applepay,ideal', $payload->servicesSelectableByClient);
    }

    /** @test */
    public function it_sets_services_excluded_for_client(): void
    {
        $payload = new PayPayload(['servicesExcludedForClient' => 'sofort']);

        $this->assertSame('sofort', $payload->servicesExcludedForClient);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $payload = new PayPayload([
            'continueOnIncomplete' => 'NoRedirect',
            'servicesSelectableByClient' => 'applepay',
            'servicesExcludedForClient' => 'creditcard,paypal',
        ]);

        $this->assertSame('NoRedirect', $payload->continueOnIncomplete);
        $this->assertSame('applepay', $payload->servicesSelectableByClient);
        $this->assertSame('creditcard,paypal', $payload->servicesExcludedForClient);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $payload = new PayPayload(['continueOnIncomplete' => 'RedirectToHTML']);

        $array = $payload->toArray();

        $this->assertIsArray($array);
        $this->assertSame('RedirectToHTML', $array['continueOnIncomplete']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $payload = new PayPayload([]);

        $array = $payload->toArray();
        $this->assertIsArray($array);
    }
}
