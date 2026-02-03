<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Blik\Models;

use Buckaroo\PaymentMethods\Blik\Models\Pay;
use Tests\TestCase;

class PayTest extends TestCase
{
    /** @test */
    public function it_sets_email(): void
    {
        $pay = new Pay(['email' => 'customer@example.com']);

        $this->assertSame('customer@example.com', $pay->email);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $pay = new Pay(['email' => 'test@test.com']);

        $array = $pay->toArray();

        $this->assertIsArray($array);
        $this->assertSame('test@test.com', $array['email']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $pay = new Pay([]);

        $array = $pay->toArray();
        $this->assertIsArray($array);
    }
}
