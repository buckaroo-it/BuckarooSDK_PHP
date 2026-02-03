<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\CreditManagement\Models;

use Buckaroo\PaymentMethods\CreditManagement\Models\AddOrUpdateProductLines;
use Buckaroo\PaymentMethods\CreditManagement\Service\ParameterKeys\ArticleAdapter;
use Tests\TestCase;

class AddOrUpdateProductLinesTest extends TestCase
{
    /** @test */
    public function it_sets_invoice_key(): void
    {
        $model = new AddOrUpdateProductLines(['invoiceKey' => 'INV-KEY-001']);

        $this->assertSame('INV-KEY-001', $model->invoiceKey);
    }

    /** @test */
    public function it_sets_articles_from_array(): void
    {
        $model = new AddOrUpdateProductLines([
            'articles' => [
                ['productLine' => 'Product A'],
                ['productLine' => 'Product B'],
            ],
        ]);

        $articles = $model->articles();

        $this->assertIsArray($articles);
        $this->assertCount(2, $articles);
        $this->assertInstanceOf(ArticleAdapter::class, $articles[0]);
        $this->assertInstanceOf(ArticleAdapter::class, $articles[1]);
    }

    /** @test */
    public function it_returns_empty_articles_array_without_parameter(): void
    {
        $model = new AddOrUpdateProductLines([]);

        $articles = $model->articles();

        $this->assertIsArray($articles);
        $this->assertEmpty($articles);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $model = new AddOrUpdateProductLines([
            'invoiceKey' => 'INV-999',
            'articles' => [
                ['productLine' => 'Item 1'],
            ],
        ]);

        $this->assertSame('INV-999', $model->invoiceKey);
        $this->assertCount(1, $model->articles());
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $model = new AddOrUpdateProductLines(['invoiceKey' => 'TEST-KEY']);

        $array = $model->toArray();

        $this->assertIsArray($array);
        $this->assertSame('TEST-KEY', $array['invoiceKey']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $model = new AddOrUpdateProductLines([]);

        $array = $model->toArray();
        $this->assertIsArray($array);
    }
}
