<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\CreditCard\Models;

use Buckaroo\PaymentMethods\CreditCard\Models\CardData;
use Tests\TestCase;

class CardDataTest extends TestCase
{
    /** @test */
    public function it_sets_encrypted_card_data_via_constructor(): void
    {
        $model = new CardData(['encryptedCardData' => 'encrypted-data-123']);

        $this->assertSame('encrypted-data-123', $model->encryptedCardData);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $model = new CardData(['encryptedCardData' => 'encrypted-data-789']);

        $array = $model->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('encryptedCardData', $array);
        $this->assertSame('encrypted-data-789', $array['encryptedCardData']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $model = new CardData([]);

        $array = $model->toArray();
        $this->assertIsArray($array);
    }
}
