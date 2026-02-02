<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Bancontact\Models;

use Buckaroo\PaymentMethods\Bancontact\Models\Pay;
use Tests\TestCase;

class PayTest extends TestCase
{
    /** @test */
    public function it_sets_save_token_true(): void
    {
        $pay = new Pay(['saveToken' => true]);

        $this->assertTrue($pay->saveToken);
    }

    /** @test */
    public function it_sets_save_token_false(): void
    {
        $pay = new Pay(['saveToken' => false]);

        $this->assertFalse($pay->saveToken);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $pay = new Pay(['saveToken' => true]);

        $array = $pay->toArray();

        $this->assertIsArray($array);
        $this->assertTrue($array['saveToken']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $pay = new Pay([]);

        $array = $pay->toArray();
        $this->assertIsArray($array);
    }
}
