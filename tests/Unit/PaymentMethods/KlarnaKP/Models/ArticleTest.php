<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\KlarnaKP\Models;

use Buckaroo\Models\Article as BaseArticle;
use Buckaroo\PaymentMethods\KlarnaKP\Models\Article;
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
        ]);

        $this->assertSame('https://shop.com/images/product.png', $article->imageUrl);
        $this->assertSame('https://shop.com/products/456', $article->productUrl);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $article = new Article([
            'imageUrl' => 'https://test.com/img.jpg',
            'productUrl' => 'https://test.com/prod',
        ]);

        $array = $article->toArray();

        $this->assertIsArray($array);
        $this->assertSame('https://test.com/img.jpg', $array['imageUrl']);
        $this->assertSame('https://test.com/prod', $array['productUrl']);
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
}
