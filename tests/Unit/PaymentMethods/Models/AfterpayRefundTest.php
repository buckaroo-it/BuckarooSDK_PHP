<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Models;

use Buckaroo\PaymentMethods\Afterpay\Models\Refund;
use Buckaroo\PaymentMethods\Afterpay\Service\ParameterKeys\ArticleAdapter;
use Tests\TestCase;

class AfterpayRefundTest extends TestCase
{
    /** @test */
    public function it_sets_articles_from_array(): void
    {
        $refund = new Refund([
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

        $articles = $refund->articles();

        $this->assertIsArray($articles);
        $this->assertCount(2, $articles);
        $this->assertInstanceOf(ArticleAdapter::class, $articles[0]);
        $this->assertInstanceOf(ArticleAdapter::class, $articles[1]);
    }

    /** @test */
    public function it_returns_empty_articles_array_without_parameter(): void
    {
        $refund = new Refund([]);

        $articles = $refund->articles();

        $this->assertIsArray($articles);
        $this->assertEmpty($articles);
    }

    /** @test */
    public function it_sets_all_properties(): void
    {
        $refund = new Refund([
            'merchantImageUrl' => 'https://example.com/image.png',
            'summaryImageUrl' => 'https://example.com/summary.png',
            'articles' => [
                ['identifier' => 'ART001', 'description' => 'Article 1', 'quantity' => 1, 'price' => 100.00],
            ],
        ]);

        $this->assertCount(1, $refund->articles());
    }
}
