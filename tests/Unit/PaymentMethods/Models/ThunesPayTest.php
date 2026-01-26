<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Models;

use Buckaroo\PaymentMethods\Thunes\Models\Pay;
use Buckaroo\PaymentMethods\Thunes\Service\ParameterKeys\ArticleAdapter;
use Tests\TestCase;

class ThunesPayTest extends TestCase
{
    /** @test */
    public function it_sets_articles_from_array(): void
    {
        $pay = new Pay([
            'articles' => [
                [
                    'identifier' => 'ART001',
                    'description' => 'Test Article',
                    'quantity' => 1,
                    'price' => 100.00,
                ],
                [
                    'identifier' => 'ART002',
                    'description' => 'Another Article',
                    'quantity' => 2,
                    'price' => 50.00,
                ],
            ],
        ]);

        $articles = $pay->articles();

        $this->assertIsArray($articles);
        $this->assertCount(2, $articles);
        $this->assertInstanceOf(ArticleAdapter::class, $articles[0]);
        $this->assertInstanceOf(ArticleAdapter::class, $articles[1]);
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
    public function it_returns_articles_without_parameter_after_setting(): void
    {
        $pay = new Pay([
            'articles' => [
                ['identifier' => 'ART001', 'description' => 'Test', 'quantity' => 1, 'price' => 10.00],
            ],
        ]);

        $articles = $pay->articles();
        $this->assertCount(1, $articles);

        $sameArticles = $pay->articles(null);
        $this->assertCount(1, $sameArticles);
    }
}
