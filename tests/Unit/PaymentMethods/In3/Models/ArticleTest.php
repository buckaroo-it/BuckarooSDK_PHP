<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\In3\Models;

use Buckaroo\Models\Article as BaseArticle;
use Buckaroo\PaymentMethods\In3\Models\Article;
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
    public function it_sets_url(): void
    {
        $article = new Article(['url' => 'https://example.com/product/123']);

        $this->assertSame('https://example.com/product/123', $article->url);
    }

    /** @test */
    public function it_sets_category(): void
    {
        $article = new Article(['category' => 'Electronics']);

        $this->assertSame('Electronics', $article->category);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $article = new Article([
            'url' => 'https://shop.example.com/item/456',
            'category' => 'Clothing',
        ]);

        $this->assertSame('https://shop.example.com/item/456', $article->url);
        $this->assertSame('Clothing', $article->category);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $article = new Article([
            'url' => 'https://test.com/product',
            'category' => 'Books',
        ]);

        $array = $article->toArray();

        $this->assertIsArray($array);
        $this->assertSame('https://test.com/product', $array['url']);
        $this->assertSame('Books', $array['category']);
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
     * @dataProvider categoryProvider
     */
    public function it_handles_various_categories(string $category): void
    {
        $article = new Article(['category' => $category]);

        $this->assertSame($category, $article->category);
    }

    public static function categoryProvider(): array
    {
        return [
            ['Electronics'],
            ['Clothing'],
            ['Home & Garden'],
            ['Sports'],
            ['Books'],
            ['Food & Beverages'],
        ];
    }
}
