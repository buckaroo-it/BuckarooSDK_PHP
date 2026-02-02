<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Subscriptions\Models;

use Buckaroo\Models\Payload\DataRequestPayload;
use Buckaroo\PaymentMethods\Subscriptions\Models\CombinedPayload;
use Tests\TestCase;

class CombinedPayloadTest extends TestCase
{
    /** @test */
    public function it_extends_data_request_payload(): void
    {
        $payload = new CombinedPayload([]);

        $this->assertInstanceOf(DataRequestPayload::class, $payload);
    }

    /** @test */
    public function it_sets_start_recurrent_true(): void
    {
        $payload = new CombinedPayload(['startRecurrent' => true]);

        $this->assertTrue($payload->startRecurrent);
    }

    /** @test */
    public function it_sets_start_recurrent_false(): void
    {
        $payload = new CombinedPayload(['startRecurrent' => false]);

        $this->assertFalse($payload->startRecurrent);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $payload = new CombinedPayload(['startRecurrent' => true]);

        $array = $payload->toArray();

        $this->assertIsArray($array);
        $this->assertTrue($array['startRecurrent']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $payload = new CombinedPayload([]);

        $array = $payload->toArray();
        $this->assertIsArray($array);
    }
}
