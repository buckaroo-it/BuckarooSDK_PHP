<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\BuckarooVoucher\Models;

use Buckaroo\Models\Payload\Payload;
use Buckaroo\PaymentMethods\BuckarooVoucher\Models\CreatePayload;
use Tests\TestCase;

class CreatePayloadTest extends TestCase
{
    /** @test */
    public function it_extends_payload(): void
    {
        $payload = new CreatePayload([]);

        $this->assertInstanceOf(Payload::class, $payload);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $payload = new CreatePayload([]);

        $array = $payload->toArray();

        $this->assertIsArray($array);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $payload = new CreatePayload([]);

        $array = $payload->toArray();
        $this->assertIsArray($array);
    }
}
