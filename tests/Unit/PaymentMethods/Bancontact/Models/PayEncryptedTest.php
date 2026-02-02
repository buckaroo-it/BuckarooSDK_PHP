<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Bancontact\Models;

use Buckaroo\PaymentMethods\Bancontact\Models\PayEncrypted;
use Tests\TestCase;

class PayEncryptedTest extends TestCase
{
    /** @test */
    public function it_sets_encrypted_card_data(): void
    {
        $payEncrypted = new PayEncrypted(['encryptedCardData' => 'ENCRYPTED_DATA_123']);

        $this->assertSame('ENCRYPTED_DATA_123', $payEncrypted->encryptedCardData);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $payEncrypted = new PayEncrypted(['encryptedCardData' => 'ENCRYPTED_DATA_456']);

        $array = $payEncrypted->toArray();

        $this->assertIsArray($array);
        $this->assertSame('ENCRYPTED_DATA_456', $array['encryptedCardData']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $payEncrypted = new PayEncrypted([]);

        $array = $payEncrypted->toArray();
        $this->assertIsArray($array);
    }
}
