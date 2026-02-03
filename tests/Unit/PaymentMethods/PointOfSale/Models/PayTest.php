<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\PointOfSale\Models;

use Buckaroo\PaymentMethods\PointOfSale\Models\Pay;
use Tests\TestCase;

class PayTest extends TestCase
{
    /** @test */
    public function it_sets_terminal_id(): void
    {
        $pay = new Pay(['terminalID' => 'TERMINAL-001']);

        $this->assertSame('TERMINAL-001', $pay->terminalID);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $pay = new Pay(['terminalID' => 'TERM-456']);

        $array = $pay->toArray();

        $this->assertIsArray($array);
        $this->assertSame('TERM-456', $array['terminalID']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $pay = new Pay([]);

        $array = $pay->toArray();
        $this->assertIsArray($array);
    }
}
