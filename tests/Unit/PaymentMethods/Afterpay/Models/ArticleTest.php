<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Afterpay\Models;

use Buckaroo\Models\Article as BaseArticle;
use Buckaroo\PaymentMethods\Afterpay\Models\Article;
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
    public function it_sets_url(): void
    {
        $article = new Article(['url' => 'https://example.com/product/123']);

        $this->assertSame('https://example.com/product/123', $article->url);
    }

    /** @test */
    public function it_sets_marketplace_seller_id(): void
    {
        $article = new Article(['marketPlaceSellerId' => 'SELLER-001']);

        $this->assertSame('SELLER-001', $article->marketPlaceSellerId);
    }

    /** @test */
    public function it_sets_refund_type(): void
    {
        $article = new Article(['refundType' => 'full']);

        $this->assertSame('full', $article->refundType);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $article = new Article([
            'imageUrl' => 'https://shop.com/img/product.png',
            'url' => 'https://shop.com/products/456',
            'marketPlaceSellerId' => 'SELLER-999',
            'refundType' => 'partial',
        ]);

        $this->assertSame('https://shop.com/img/product.png', $article->imageUrl);
        $this->assertSame('https://shop.com/products/456', $article->url);
        $this->assertSame('SELLER-999', $article->marketPlaceSellerId);
        $this->assertSame('partial', $article->refundType);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $article = new Article([
            'imageUrl' => 'https://test.com/img.jpg',
            'refundType' => 'full',
        ]);

        $array = $article->toArray();

        $this->assertIsArray($array);
        $this->assertSame('https://test.com/img.jpg', $array['imageUrl']);
        $this->assertSame('full', $array['refundType']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $article = new Article([]);

        $array = $article->toArray();
        $this->assertIsArray($array);
    }

    /**
     * @test
     * @dataProvider refundTypeProvider
     */
    public function it_handles_various_refund_types(string $refundType): void
    {
        $article = new Article(['refundType' => $refundType]);

        $this->assertSame($refundType, $article->refundType);
    }

    public static function refundTypeProvider(): array
    {
        return [
            ['full'],
            ['partial'],
            ['return'],
            ['cancel'],
        ];
    }
}
