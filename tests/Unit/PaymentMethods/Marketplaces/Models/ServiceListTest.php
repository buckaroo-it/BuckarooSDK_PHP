<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Marketplaces\Models;

use Buckaroo\PaymentMethods\Marketplaces\Models\Marketplace;
use Buckaroo\PaymentMethods\Marketplaces\Models\Seller;
use Buckaroo\PaymentMethods\Marketplaces\Models\ServiceList;
use Tests\TestCase;

class ServiceListTest extends TestCase
{
    /** @test */
    public function it_sets_days_until_transfer(): void
    {
        $serviceList = new ServiceList(['daysUntilTransfer' => '7']);

        $this->assertSame('7', $serviceList->daysUntilTransfer);
    }

    /** @test */
    public function it_sets_marketplace_from_array(): void
    {
        $serviceList = new ServiceList([
            'marketplace' => [
                'amount' => 100.00,
                'description' => 'Test marketplace',
            ],
        ]);

        $marketplace = $serviceList->marketplace();

        $this->assertInstanceOf(Marketplace::class, $marketplace);
    }

    /** @test */
    public function it_sets_sellers_from_array(): void
    {
        $serviceList = new ServiceList([
            'sellers' => [
                ['accountId' => 'SELLER-001', 'amount' => 50.00],
                ['accountId' => 'SELLER-002', 'amount' => 50.00],
            ],
        ]);

        $sellers = $serviceList->sellers();

        $this->assertIsArray($sellers);
        $this->assertCount(2, $sellers);
        $this->assertInstanceOf(Seller::class, $sellers[0]);
        $this->assertInstanceOf(Seller::class, $sellers[1]);
    }

    /** @test */
    public function it_returns_empty_sellers_array_without_parameter(): void
    {
        $serviceList = new ServiceList([]);

        $sellers = $serviceList->sellers();

        $this->assertIsArray($sellers);
        $this->assertEmpty($sellers);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $serviceList = new ServiceList([
            'daysUntilTransfer' => '14',
            'marketplace' => ['amount' => 200.00],
            'sellers' => [
                ['accountId' => 'SELLER-A', 'amount' => 100.00],
            ],
        ]);

        $this->assertSame('14', $serviceList->daysUntilTransfer);
        $this->assertInstanceOf(Marketplace::class, $serviceList->marketplace());
        $this->assertCount(1, $serviceList->sellers());
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $serviceList = new ServiceList(['daysUntilTransfer' => '30']);

        $array = $serviceList->toArray();

        $this->assertIsArray($array);
        $this->assertSame('30', $array['daysUntilTransfer']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $serviceList = new ServiceList([]);

        $array = $serviceList->toArray();
        $this->assertIsArray($array);
    }
}
