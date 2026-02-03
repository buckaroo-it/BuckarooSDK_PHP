<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\KlarnaPay\Models;

use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\KlarnaPay\Models\Pay;
use Buckaroo\PaymentMethods\KlarnaPay\Models\Recipient;
use Buckaroo\PaymentMethods\KlarnaPay\Service\ParameterKeys\ArticleAdapter;
use Tests\TestCase;

class PayTest extends TestCase
{
    /** @test */
    public function it_extends_service_parameter(): void
    {
        $pay = new Pay([]);

        $this->assertInstanceOf(ServiceParameter::class, $pay);
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
    public function it_creates_both_billing_and_shipping_when_billing_set(): void
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
        $shipping = $pay->shipping();

        $this->assertInstanceOf(Recipient::class, $billing);
        $this->assertInstanceOf(Recipient::class, $shipping);
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
    }

    /** @test */
    public function it_sets_articles_from_array(): void
    {
        $pay = new Pay([
            'articles' => [
                ['identifier' => 'ART-001', 'description' => 'Test Article'],
            ],
        ]);

        $articles = $pay->articles();

        $this->assertIsArray($articles);
        $this->assertCount(1, $articles);
        $this->assertInstanceOf(ArticleAdapter::class, $articles[0]);
    }

    /** @test */
    public function it_sets_multiple_articles(): void
    {
        $pay = new Pay([
            'articles' => [
                ['identifier' => 'ART-001', 'description' => 'First Article'],
                ['identifier' => 'ART-002', 'description' => 'Second Article'],
                ['identifier' => 'ART-003', 'description' => 'Third Article'],
            ],
        ]);

        $articles = $pay->articles();

        $this->assertIsArray($articles);
        $this->assertCount(3, $articles);
        $this->assertInstanceOf(ArticleAdapter::class, $articles[0]);
        $this->assertInstanceOf(ArticleAdapter::class, $articles[1]);
        $this->assertInstanceOf(ArticleAdapter::class, $articles[2]);
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
    public function it_returns_existing_billing_when_called_without_parameter(): void
    {
        $pay = new Pay([
            'billing' => [
                'recipient' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ],
            ],
        ]);

        $first = $pay->billing();
        $second = $pay->billing();

        $this->assertSame($first, $second);
    }

    /** @test */
    public function it_returns_existing_shipping_when_called_without_parameter(): void
    {
        $pay = new Pay([
            'billing' => [
                'recipient' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ],
            ],
        ]);

        $first = $pay->shipping();
        $second = $pay->shipping();

        $this->assertSame($first, $second);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $pay = new Pay([
            'billing' => [
                'recipient' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ],
                'address' => [
                    'street' => 'Billing Street',
                    'houseNumber' => '100',
                ],
            ],
            'shipping' => [
                'recipient' => [
                    'firstName' => 'Jane',
                    'lastName' => 'Smith',
                ],
                'address' => [
                    'street' => 'Shipping Street',
                    'houseNumber' => '200',
                ],
            ],
            'articles' => [
                ['identifier' => 'PROD-001', 'description' => 'Product 1', 'quantity' => 2],
                ['identifier' => 'PROD-002', 'description' => 'Product 2', 'quantity' => 1],
            ],
        ]);

        $this->assertInstanceOf(Recipient::class, $pay->billing());
        $this->assertInstanceOf(Recipient::class, $pay->shipping());
        $this->assertCount(2, $pay->articles());
    }

    /** @test */
    public function it_has_countable_properties_for_articles(): void
    {
        $pay = new Pay([]);

        $reflection = new \ReflectionClass($pay);
        $property = $reflection->getProperty('countableProperties');
        $property->setAccessible(true);

        $countable = $property->getValue($pay);

        $this->assertContains('articles', $countable);
    }

    /** @test */
    public function it_has_group_data_for_articles(): void
    {
        $pay = new Pay([]);

        $reflection = new \ReflectionClass($pay);
        $property = $reflection->getProperty('groupData');
        $property->setAccessible(true);

        $groupData = $property->getValue($pay);

        $this->assertArrayHasKey('articles', $groupData);
        $this->assertSame('Article', $groupData['articles']['groupType']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $pay = new Pay([]);

        $this->assertInstanceOf(Pay::class, $pay);
    }
}
