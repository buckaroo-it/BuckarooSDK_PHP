<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\AfterpayDigiAccept\Models;

use Buckaroo\PaymentMethods\AfterpayDigiAccept\Models\Pay;
use Buckaroo\PaymentMethods\AfterpayDigiAccept\Models\Recipient;
use Buckaroo\PaymentMethods\AfterpayDigiAccept\Service\ParameterKeys\ArticleAdapter;
use Tests\TestCase;

class PayTest extends TestCase
{
    /** @test */
    public function it_sets_b2b(): void
    {
        $pay = new Pay(['b2B' => true]);

        $this->assertTrue($pay->b2B);
    }

    /** @test */
    public function it_sets_addresses_differ(): void
    {
        $pay = new Pay(['addressesDiffer' => true]);

        $this->assertTrue($pay->addressesDiffer);
    }

    /** @test */
    public function it_sets_customer_ip_address(): void
    {
        $pay = new Pay(['customerIPAddress' => '192.168.1.1']);

        $this->assertSame('192.168.1.1', $pay->customerIPAddress);
    }

    /** @test */
    public function it_sets_shipping_costs(): void
    {
        $pay = new Pay(['shippingCosts' => 5.95]);

        $this->assertSame(5.95, $pay->shippingCosts);
    }

    /** @test */
    public function it_sets_cost_centre(): void
    {
        $pay = new Pay(['costCentre' => 'COST-001']);

        $this->assertSame('COST-001', $pay->costCentre);
    }

    /** @test */
    public function it_sets_department(): void
    {
        $pay = new Pay(['department' => 'IT Department']);

        $this->assertSame('IT Department', $pay->department);
    }

    /** @test */
    public function it_sets_establishment_number(): void
    {
        $pay = new Pay(['establishmentNumber' => 'EST-12345']);

        $this->assertSame('EST-12345', $pay->establishmentNumber);
    }

    /** @test */
    public function it_sets_billing_recipient_from_array(): void
    {
        $pay = new Pay([
            'billing' => [
                'recipient' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ],
            ],
        ]);

        $billing = $pay->billing();

        $this->assertInstanceOf(Recipient::class, $billing);
    }

    /** @test */
    public function it_sets_articles_from_array(): void
    {
        $pay = new Pay([
            'articles' => [
                ['identifier' => 'ART-001', 'description' => 'Test'],
            ],
        ]);

        $articles = $pay->articles();

        $this->assertIsArray($articles);
        $this->assertCount(1, $articles);
        $this->assertInstanceOf(ArticleAdapter::class, $articles[0]);
    }

    /** @test */
    public function it_returns_empty_articles_array_without_parameter(): void
    {
        $pay = new Pay([]);

        $articles = $pay->articles();

        $this->assertIsArray($articles);
        $this->assertEmpty($articles);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $pay = new Pay([]);

        $array = $pay->toArray();
        $this->assertIsArray($array);
    }

    /** @test */
    public function it_sets_shipping_recipient_from_array(): void
    {
        $pay = new Pay([
            'billing' => [
                'recipient' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ],
            ],
            'shipping' => [
                'recipient' => [
                    'firstName' => 'Jane',
                    'lastName' => 'Smith',
                ],
            ],
        ]);

        $shipping = $pay->shipping();

        $this->assertInstanceOf(Recipient::class, $shipping);
        $this->assertTrue($pay->addressesDiffer);
    }

    /** @test */
    public function it_returns_shipping_recipient_without_parameter(): void
    {
        $pay = new Pay([
            'billing' => [
                'recipient' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ],
            ],
        ]);

        // When billing is set without shipping, shipping is auto-set from billing
        $shipping = $pay->shipping();

        $this->assertInstanceOf(Recipient::class, $shipping);
    }
}
