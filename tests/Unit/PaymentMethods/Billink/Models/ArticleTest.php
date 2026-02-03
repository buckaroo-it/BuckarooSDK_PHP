<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Billink\Models;

use Buckaroo\Models\Article as BaseArticle;
use Buckaroo\PaymentMethods\Billink\Models\Article;
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
    public function it_sets_price_excl(): void
    {
        $article = new Article(['priceExcl' => 99.99]);

        $this->assertSame(99.99, $article->priceExcl);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $article = new Article(['priceExcl' => 49.95]);

        $array = $article->toArray();

        $this->assertIsArray($array);
        $this->assertSame(49.95, $array['priceExcl']);
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
     * @dataProvider priceProvider
     */
    public function it_handles_various_prices(float $price): void
    {
        $article = new Article(['priceExcl' => $price]);

        $this->assertSame($price, $article->priceExcl);
    }

    public static function priceProvider(): array
    {
        return [
            [0.01],
            [9.99],
            [100.00],
            [999.99],
            [1234.56],
        ];
    }
}
