<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\CreditManagement\Models;

use Buckaroo\Models\Article as BaseArticle;
use Buckaroo\PaymentMethods\CreditManagement\Models\Article;
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
    public function it_sets_product_line(): void
    {
        $article = new Article(['productLine' => 'Electronics']);

        $this->assertSame('Electronics', $article->productLine);
    }

    /** @test */
    public function it_sets_product_group_name(): void
    {
        $article = new Article(['productGroupName' => 'Computers']);

        $this->assertSame('Computers', $article->productGroupName);
    }

    /** @test */
    public function it_sets_product_group_order_index(): void
    {
        $article = new Article(['productGroupOrderIndex' => '1']);

        $this->assertSame('1', $article->productGroupOrderIndex);
    }

    /** @test */
    public function it_sets_product_order_index(): void
    {
        $article = new Article(['productOrderIndex' => '5']);

        $this->assertSame('5', $article->productOrderIndex);
    }

    /** @test */
    public function it_sets_unit_of_measurement(): void
    {
        $article = new Article(['unitOfMeasurement' => 'pcs']);

        $this->assertSame('pcs', $article->unitOfMeasurement);
    }

    /** @test */
    public function it_sets_discount_percentage(): void
    {
        $article = new Article(['discountPercentage' => 10.5]);

        $this->assertSame(10.5, $article->discountPercentage);
    }

    /** @test */
    public function it_sets_total_discount(): void
    {
        $article = new Article(['totalDiscount' => 25.00]);

        $this->assertSame(25.00, $article->totalDiscount);
    }

    /** @test */
    public function it_sets_total_vat(): void
    {
        $article = new Article(['totalVat' => 21.00]);

        $this->assertSame(21.00, $article->totalVat);
    }

    /** @test */
    public function it_sets_total_amount_ex_vat(): void
    {
        $article = new Article(['totalAmountExVat' => 100.00]);

        $this->assertSame(100.00, $article->totalAmountExVat);
    }

    /** @test */
    public function it_sets_total_amount(): void
    {
        $article = new Article(['totalAmount' => 121.00]);

        $this->assertSame(121.00, $article->totalAmount);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $article = new Article([
            'productLine' => 'Software',
            'productGroupName' => 'Subscriptions',
            'productGroupOrderIndex' => '2',
            'productOrderIndex' => '10',
            'unitOfMeasurement' => 'license',
            'discountPercentage' => 15.0,
            'totalDiscount' => 30.00,
            'totalVat' => 42.00,
            'totalAmountExVat' => 200.00,
            'totalAmount' => 242.00,
        ]);

        $this->assertSame('Software', $article->productLine);
        $this->assertSame('Subscriptions', $article->productGroupName);
        $this->assertSame('2', $article->productGroupOrderIndex);
        $this->assertSame('10', $article->productOrderIndex);
        $this->assertSame('license', $article->unitOfMeasurement);
        $this->assertSame(15.0, $article->discountPercentage);
        $this->assertSame(30.00, $article->totalDiscount);
        $this->assertSame(42.00, $article->totalVat);
        $this->assertSame(200.00, $article->totalAmountExVat);
        $this->assertSame(242.00, $article->totalAmount);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $article = new Article([
            'productLine' => 'Test Line',
            'totalAmount' => 50.00,
        ]);

        $array = $article->toArray();

        $this->assertIsArray($array);
        $this->assertSame('Test Line', $array['productLine']);
        $this->assertSame(50.00, $array['totalAmount']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $article = new Article([]);

        $array = $article->toArray();
        $this->assertIsArray($array);
    }
}
