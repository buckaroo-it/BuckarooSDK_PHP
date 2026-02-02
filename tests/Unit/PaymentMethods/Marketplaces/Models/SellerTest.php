<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Marketplaces\Models;

use Buckaroo\PaymentMethods\Marketplaces\Models\Seller;
use Tests\TestCase;

class SellerTest extends TestCase
{
    /** @test */
    public function it_sets_account_id_property(): void
    {
        $seller = new Seller(['accountId' => 'SELLER-123']);

        $this->assertSame('SELLER-123', $seller->accountId);
    }

    /** @test */
    public function it_sets_amount_property(): void
    {
        $seller = new Seller(['amount' => 99.99]);

        $this->assertSame(99.99, $seller->amount);
    }

    /** @test */
    public function it_sets_description_property(): void
    {
        $seller = new Seller(['description' => 'Payment to seller']);

        $this->assertSame('Payment to seller', $seller->description);
    }

    /** @test */
    public function it_sets_multiple_properties_via_constructor(): void
    {
        $seller = new Seller([
            'accountId' => 'MERCHANT-456',
            'amount' => 150.00,
            'description' => 'Product sale',
        ]);

        $this->assertSame('MERCHANT-456', $seller->accountId);
        $this->assertSame(150.00, $seller->amount);
        $this->assertSame('Product sale', $seller->description);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $seller = new Seller([
            'accountId' => 'SELLER-999',
            'amount' => 200.00,
            'description' => 'Marketplace transaction',
        ]);

        $array = $seller->toArray();

        $this->assertIsArray($array);
        $this->assertSame('SELLER-999', $array['accountId']);
        $this->assertSame(200.00, $array['amount']);
        $this->assertSame('Marketplace transaction', $array['description']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $seller = new Seller([]);

        $array = $seller->toArray();
        $this->assertIsArray($array);
    }
}
