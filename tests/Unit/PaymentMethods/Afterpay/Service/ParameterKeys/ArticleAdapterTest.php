<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Afterpay\Service\ParameterKeys;

use Buckaroo\Models\Article;
use Buckaroo\PaymentMethods\Afterpay\Service\ParameterKeys\ArticleAdapter;
use Tests\TestCase;

class ArticleAdapterTest extends TestCase
{
    public function test_transforms_price_to_gross_unit_price(): void
    {
        $article = new Article(['price' => 10.50]);
        $adapter = new ArticleAdapter($article);

        $this->assertSame('GrossUnitPrice', $adapter->serviceParameterKeyOf('price'));
    }

    public function test_uses_ucfirst_for_unmapped_properties(): void
    {
        $article = new Article(['identifier' => 'SKU-123']);
        $adapter = new ArticleAdapter($article);

        $this->assertSame('Identifier', $adapter->serviceParameterKeyOf('identifier'));
        $this->assertSame('Description', $adapter->serviceParameterKeyOf('description'));
        $this->assertSame('Quantity', $adapter->serviceParameterKeyOf('quantity'));
    }

    public function test_proxies_property_access_to_model(): void
    {
        $article = new Article([
            'identifier' => 'PROD-001',
            'description' => 'Test Product',
            'quantity' => 2,
            'price' => 25.99,
        ]);

        $adapter = new ArticleAdapter($article);

        $this->assertSame('PROD-001', $adapter->identifier);
        $this->assertSame('Test Product', $adapter->description);
        $this->assertSame(2, $adapter->quantity);
        $this->assertSame(25.99, $adapter->price);
    }

    public function test_delegates_to_array_to_model(): void
    {
        $article = new Article([
            'identifier' => 'ITEM-123',
            'price' => 15.50,
            'quantity' => 3,
        ]);

        $adapter = new ArticleAdapter($article);
        $array = $adapter->toArray();

        $this->assertIsArray($array);
        $this->assertSame('ITEM-123', $array['identifier']);
        $this->assertSame(15.50, $array['price']);
        $this->assertSame(3, $array['quantity']);
    }

    public function test_handles_various_price_values(): void
    {
        $testCases = [
            0.01,
            9.99,
            100.00,
            1234.56,
            0.00,
        ];

        foreach ($testCases as $price) {
            $article = new Article(['price' => $price]);
            $adapter = new ArticleAdapter($article);

            $this->assertSame($price, $adapter->price);
            $this->assertSame('GrossUnitPrice', $adapter->serviceParameterKeyOf('price'));
        }
    }
}
