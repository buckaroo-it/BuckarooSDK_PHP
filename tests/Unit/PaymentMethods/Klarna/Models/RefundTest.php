<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Klarna\Models;

use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Klarna\Models\Refund;
use Buckaroo\PaymentMethods\Klarna\Service\ParameterKeys\ArticleAdapter;
use Tests\TestCase;

class RefundTest extends TestCase
{
    /** @test */
    public function it_is_a_service_parameter(): void
    {
        $this->assertInstanceOf(ServiceParameter::class, new Refund());
    }

    /** @test */
    public function it_wraps_each_article_in_the_method_article_adapter(): void
    {
        $refund = new Refund([
            'articles' => [
                ['identifier' => 'SKU-001', 'quantity' => 2],
                ['identifier' => 'SKU-002', 'quantity' => 1],
            ],
        ]);

        $articles = $refund->articles();

        $this->assertCount(2, $articles);
        $this->assertContainsOnlyInstancesOf(ArticleAdapter::class, $articles);
        $this->assertSame('SKU-001', $articles[0]->identifier);
        $this->assertSame(2, $articles[0]->quantity);
    }

    /** @test */
    public function it_returns_empty_articles_without_payload(): void
    {
        $this->assertSame([], (new Refund([]))->articles());
        $this->assertSame([], (new Refund())->articles());
    }

    /** @test */
    public function it_ignores_unrelated_payload_keys(): void
    {
        $refund = new Refund([
            'amountCredit' => 10.0,
            'invoice' => 'INV-001',
            'originalTransactionKey' => 'KEY',
        ]);

        $this->assertSame(['articles' => []], array_intersect_key($refund->toArray(), ['articles' => true]));
        $this->assertArrayNotHasKey('amountCredit', $refund->toArray());
        $this->assertArrayNotHasKey('invoice', $refund->toArray());
    }

    /** @test */
    public function it_numbers_article_groups_from_one(): void
    {
        $refund = new Refund();

        $this->assertSame('Article', $refund->getGroupType('articles'));
        $this->assertSame(1, $refund->getGroupKey('articles', 0));
        $this->assertSame(2, $refund->getGroupKey('articles', 1));
    }
}
