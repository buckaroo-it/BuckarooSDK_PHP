<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Marketplaces\Models;

use Buckaroo\PaymentMethods\Marketplaces\Models\Marketplace;
use Tests\TestCase;

class MarketplaceTest extends TestCase
{
    /** @test */
    public function it_sets_amount_property(): void
    {
        $marketplace = new Marketplace(['amount' => 10.50]);

        $this->assertSame(10.50, $marketplace->amount);
    }

    /** @test */
    public function it_sets_description_property(): void
    {
        $marketplace = new Marketplace(['description' => 'Marketplace fee']);

        $this->assertSame('Marketplace fee', $marketplace->description);
    }

    /** @test */
    public function it_sets_multiple_properties_via_constructor(): void
    {
        $marketplace = new Marketplace([
            'amount' => 25.99,
            'description' => 'Platform commission',
        ]);

        $this->assertSame(25.99, $marketplace->amount);
        $this->assertSame('Platform commission', $marketplace->description);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $marketplace = new Marketplace([
            'amount' => 30.00,
            'description' => 'Transaction fee',
        ]);

        $array = $marketplace->toArray();

        $this->assertIsArray($array);
        $this->assertSame(30.00, $array['amount']);
        $this->assertSame('Transaction fee', $array['description']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $marketplace = new Marketplace([]);

        $array = $marketplace->toArray();
        $this->assertIsArray($array);
    }
}
