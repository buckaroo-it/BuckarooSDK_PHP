<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\BuckarooWallet\Models;

use Buckaroo\Models\Payload\Payload;
use Buckaroo\PaymentMethods\BuckarooWallet\Models\ReleasePayload;
use Tests\TestCase;

class ReleasePayloadTest extends TestCase
{
    /** @test */
    public function it_extends_payload_model(): void
    {
        $payload = new ReleasePayload([]);

        $this->assertInstanceOf(Payload::class, $payload);
    }

    /** @test */
    public function it_sets_amount_credit(): void
    {
        $payload = new ReleasePayload(['amountCredit' => 75.00]);

        $this->assertSame(75.00, $payload->amountCredit);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $payload = new ReleasePayload(['amountCredit' => 200.00]);

        $array = $payload->toArray();

        $this->assertIsArray($array);
        $this->assertSame(200.00, $array['amountCredit']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $payload = new ReleasePayload([]);

        $array = $payload->toArray();
        $this->assertIsArray($array);
    }

    /**
     * @test
     * @dataProvider amountProvider
     */
    public function it_handles_various_amounts(float $amount): void
    {
        $payload = new ReleasePayload(['amountCredit' => $amount]);

        $this->assertSame($amount, $payload->amountCredit);
    }

    public static function amountProvider(): array
    {
        return [
            [0.01],
            [10.00],
            [99.99],
            [500.00],
            [1000.00],
        ];
    }
}
