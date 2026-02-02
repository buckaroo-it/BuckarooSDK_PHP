<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\CreditCard\Models;

use Buckaroo\PaymentMethods\CreditCard\Models\SecurityCode;
use Tests\TestCase;

class SecurityCodeTest extends TestCase
{
    /** @test */
    public function it_sets_encrypted_security_code_via_constructor(): void
    {
        $model = new SecurityCode(['encryptedSecurityCode' => 'encrypted-cvv-123']);

        $this->assertSame('encrypted-cvv-123', $model->encryptedSecurityCode);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $model = new SecurityCode(['encryptedSecurityCode' => 'encrypted-cvv-789']);

        $array = $model->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('encryptedSecurityCode', $array);
        $this->assertSame('encrypted-cvv-789', $array['encryptedSecurityCode']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $model = new SecurityCode([]);

        $array = $model->toArray();
        $this->assertIsArray($array);
    }
}
