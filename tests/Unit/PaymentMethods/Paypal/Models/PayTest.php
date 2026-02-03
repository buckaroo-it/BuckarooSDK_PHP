<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Paypal\Models;

use Buckaroo\PaymentMethods\Paypal\Models\Pay;
use Tests\TestCase;

class PayTest extends TestCase
{
    /** @test */
    public function it_sets_buyer_email(): void
    {
        $pay = new Pay(['buyerEmail' => 'buyer@example.com']);

        $this->assertSame('buyer@example.com', $pay->buyerEmail);
    }

    /** @test */
    public function it_sets_product_name(): void
    {
        $pay = new Pay(['productName' => 'Test Product']);

        $this->assertSame('Test Product', $pay->productName);
    }

    /** @test */
    public function it_sets_billing_agreement_description(): void
    {
        $pay = new Pay(['billingAgreementDescription' => 'Monthly Subscription']);

        $this->assertSame('Monthly Subscription', $pay->billingAgreementDescription);
    }

    /** @test */
    public function it_sets_page_style(): void
    {
        $pay = new Pay(['pageStyle' => 'custom-style']);

        $this->assertSame('custom-style', $pay->pageStyle);
    }

    /** @test */
    public function it_sets_start_recurrent(): void
    {
        $pay = new Pay(['startrecurrent' => 'true']);

        $this->assertSame('true', $pay->startrecurrent);
    }

    /** @test */
    public function it_sets_paypal_order_id(): void
    {
        $pay = new Pay(['payPalOrderId' => 'ORDER-123456']);

        $this->assertSame('ORDER-123456', $pay->payPalOrderId);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $pay = new Pay([
            'buyerEmail' => 'john@example.com',
            'productName' => 'Premium Plan',
            'billingAgreementDescription' => 'Yearly subscription',
            'pageStyle' => 'brand-style',
            'startrecurrent' => 'false',
            'payPalOrderId' => 'ORDER-999',
        ]);

        $this->assertSame('john@example.com', $pay->buyerEmail);
        $this->assertSame('Premium Plan', $pay->productName);
        $this->assertSame('Yearly subscription', $pay->billingAgreementDescription);
        $this->assertSame('brand-style', $pay->pageStyle);
        $this->assertSame('false', $pay->startrecurrent);
        $this->assertSame('ORDER-999', $pay->payPalOrderId);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $pay = new Pay([
            'buyerEmail' => 'test@test.com',
            'productName' => 'Test Item',
        ]);

        $array = $pay->toArray();

        $this->assertIsArray($array);
        $this->assertSame('test@test.com', $array['buyerEmail']);
        $this->assertSame('Test Item', $array['productName']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $pay = new Pay([]);

        $array = $pay->toArray();
        $this->assertIsArray($array);
    }
}
