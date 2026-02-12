<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Klarna\Models;

use Buckaroo\Models\Article as BaseArticle;
use Buckaroo\PaymentMethods\Klarna\Models\Article;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    /** @test */
    public function it_extends_base_article_model(): void
    {
        $article = new Article([]);

        $this->assertInstanceOf(BaseArticle::class, $article);
    }

    /** @test */
    public function it_sets_image_url(): void
    {
        $article = new Article(['imageUrl' => 'https://example.com/image.jpg']);

        $this->assertSame('https://example.com/image.jpg', $article->imageUrl);
    }

    /** @test */
    public function it_sets_product_url(): void
    {
        $article = new Article(['productUrl' => 'https://example.com/product/123']);

        $this->assertSame('https://example.com/product/123', $article->productUrl);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $article = new Article([
            'imageUrl' => 'https://shop.com/images/product.png',
            'productUrl' => 'https://shop.com/products/456',
            'identifier' => 'SKU-001',
            'description' => 'Test Product',
            'quantity' => 2,
            'price' => 50.00,
            'vatPercentage' => 21,
        ]);

        $this->assertSame('https://shop.com/images/product.png', $article->imageUrl);
        $this->assertSame('https://shop.com/products/456', $article->productUrl);
        $this->assertSame('SKU-001', $article->identifier);
        $this->assertSame('Test Product', $article->description);
        $this->assertSame(2, $article->quantity);
        $this->assertSame(50.00, $article->price);
        $this->assertEquals(21, $article->vatPercentage);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $article = new Article([
            'imageUrl' => 'https://test.com/img.jpg',
            'productUrl' => 'https://test.com/prod',
            'identifier' => 'TEST-001',
            'description' => 'Test Item',
        ]);

        $array = $article->toArray();

        $this->assertIsArray($array);
        $this->assertSame('https://test.com/img.jpg', $array['imageUrl']);
        $this->assertSame('https://test.com/prod', $array['productUrl']);
        $this->assertSame('TEST-001', $array['identifier']);
        $this->assertSame('Test Item', $array['description']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $article = new Article([]);

        $array = $article->toArray();
        $this->assertIsArray($array);
    }

    /** @test */
    public function it_handles_null_image_url(): void
    {
        $article = new Article(['imageUrl' => null]);

        $this->assertNull($article->imageUrl);
    }

    /** @test */
    public function it_handles_null_product_url(): void
    {
        $article = new Article(['productUrl' => null]);

        $this->assertNull($article->productUrl);
    }

    /** @test */
    public function it_inherits_base_article_properties(): void
    {
        $article = new Article([
            'identifier' => 'PROD-123',
            'description' => 'Premium Product',
            'quantity' => 3,
            'price' => 99.99,
            'vatPercentage' => 21,
        ]);

        $this->assertSame('PROD-123', $article->identifier);
        $this->assertSame('Premium Product', $article->description);
        $this->assertSame(3, $article->quantity);
        $this->assertSame(99.99, $article->price);
        $this->assertEquals(21, $article->vatPercentage);
    }
}
