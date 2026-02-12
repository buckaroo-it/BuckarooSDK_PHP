<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Klarna\Models;

use Buckaroo\PaymentMethods\Klarna\Models\Payload;
use Buckaroo\PaymentMethods\Klarna\Models\Recipient;
use Buckaroo\PaymentMethods\Klarna\Models\ShippingInfo;
use Buckaroo\PaymentMethods\Klarna\Service\ParameterKeys\ArticleAdapter;
use Tests\TestCase;

class PayloadTest extends TestCase
{
    /** @test */
    public function it_sets_and_returns_billing_recipient(): void
    {
        $payload = new Payload([
            'billing' => [
                'recipient' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ],
            ],
        ]);

        $this->assertInstanceOf(Recipient::class, $payload->billing());
    }

    /** @test */
    public function it_sets_shipping_same_as_billing_by_default(): void
    {
        $payload = new Payload([
            'billing' => [
                'recipient' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ],
            ],
        ]);

        // When only billing is set, shipping should be same as billing
        $this->assertInstanceOf(Recipient::class, $payload->billing());
        $this->assertTrue($payload->shippingSameAsBilling);
    }

    /** @test */
    public function it_sets_and_returns_separate_shipping_recipient(): void
    {
        $payload = new Payload([
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

        $this->assertInstanceOf(Recipient::class, $payload->shipping());
        $this->assertFalse($payload->shippingSameAsBilling);
    }

    /** @test */
    public function it_sets_and_returns_articles(): void
    {
        $payload = new Payload([
            'articles' => [
                [
                    'identifier' => 'ART001',
                    'description' => 'Product 1',
                    'quantity' => 1,
                    'price' => 10.00,
                    'vatPercentage' => 21,
                ],
                [
                    'identifier' => 'ART002',
                    'description' => 'Product 2',
                    'quantity' => 2,
                    'price' => 20.00,
                    'vatPercentage' => 21,
                ],
            ],
        ]);

        $articles = $payload->articles();

        $this->assertIsArray($articles);
        $this->assertCount(2, $articles);
        foreach ($articles as $article) {
            $this->assertInstanceOf(ArticleAdapter::class, $article);
        }
    }

    /** @test */
    public function it_sets_and_returns_shipping_info(): void
    {
        $payload = new Payload([
            'shippingInfo' => [
                'company' => 'DHL',
                'trackingNumber' => 'TRACK-123',
                'shippingMethod' => 'Next Day',
            ],
        ]);

        $shippingInfo = $payload->shippingInfo();

        $this->assertInstanceOf(ShippingInfo::class, $shippingInfo);
    }

    /** @test */
    public function it_can_be_created_with_all_properties(): void
    {
        $payload = new Payload([
            'gender' => 1,
            'operatingCountry' => 'NL',
            'pno' => '12345678',
            'dataRequestKey' => 'DR-001',
        ]);

        $this->assertSame(1, $payload->gender);
        $this->assertSame('NL', $payload->operatingCountry);
        $this->assertSame('12345678', $payload->pno);
        $this->assertSame('DR-001', $payload->dataRequestKey);
    }

    /** @test */
    public function it_handles_empty_articles_array(): void
    {
        $payload = new Payload(['articles' => []]);

        $articles = $payload->articles();

        $this->assertIsArray($articles);
        $this->assertCount(0, $articles);
    }

    /** @test */
    public function it_handles_complete_data_structure(): void
    {
        $payload = new Payload([
            'billing' => [
                'recipient' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ],
                'address' => [
                    'street' => 'Main Street',
                    'houseNumber' => '123',
                    'zipcode' => '1234AB',
                    'city' => 'Amsterdam',
                    'country' => 'NL',
                ],
                'email' => 'john@example.com',
            ],
            'shipping' => [
                'recipient' => [
                    'firstName' => 'Jane',
                    'lastName' => 'Smith',
                ],
            ],
            'articles' => [
                [
                    'identifier' => 'SKU001',
                    'description' => 'Test Product',
                    'quantity' => 1,
                    'price' => 50.00,
                    'vatPercentage' => 21,
                ],
            ],
            'shippingInfo' => [
                'company' => 'FedEx',
                'trackingNumber' => 'FX123456',
                'shippingMethod' => 'Express',
            ],
        ]);

        $this->assertInstanceOf(Recipient::class, $payload->billing());
        $this->assertInstanceOf(Recipient::class, $payload->shipping());
        $this->assertIsArray($payload->articles());
        $this->assertInstanceOf(ShippingInfo::class, $payload->shippingInfo());
        $this->assertFalse($payload->shippingSameAsBilling);
    }
}
