<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Afterpay\Models;

use Buckaroo\PaymentMethods\Afterpay\Models\Pay;
use Buckaroo\PaymentMethods\Afterpay\Models\Recipient;
use Buckaroo\PaymentMethods\Afterpay\Service\ParameterKeys\ArticleAdapter;
use Tests\TestCase;

class PayTest extends TestCase
{
    /** @test */
    public function it_sets_merchant_image_url(): void
    {
        $pay = new Pay(['merchantImageUrl' => 'https://example.com/logo.png']);

        $this->assertSame('https://example.com/logo.png', $pay->merchantImageUrl);
    }

    /** @test */
    public function it_sets_summary_image_url(): void
    {
        $pay = new Pay(['summaryImageUrl' => 'https://example.com/summary.png']);

        $this->assertSame('https://example.com/summary.png', $pay->summaryImageUrl);
    }

    /** @test */
    public function it_sets_bank_account(): void
    {
        $pay = new Pay(['bankAccount' => 'NL91ABNA0417164300']);

        $this->assertSame('NL91ABNA0417164300', $pay->bankAccount);
    }

    /** @test */
    public function it_sets_bank_code(): void
    {
        $pay = new Pay(['bankCode' => 'ABNANL2A']);

        $this->assertSame('ABNANL2A', $pay->bankCode);
    }

    /** @test */
    public function it_sets_your_reference(): void
    {
        $pay = new Pay(['yourReference' => 'YOUR-REF-001']);

        $this->assertSame('YOUR-REF-001', $pay->yourReference);
    }

    /** @test */
    public function it_sets_our_reference(): void
    {
        $pay = new Pay(['ourReference' => 'OUR-REF-001']);

        $this->assertSame('OUR-REF-001', $pay->ourReference);
    }

    /** @test */
    public function it_sets_billing_recipient_from_array(): void
    {
        $pay = new Pay([
            'billing' => [
                'recipient' => [
                    'category' => 'Person',
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ],
            ],
        ]);

        $billing = $pay->billing();

        $this->assertInstanceOf(Recipient::class, $billing);
    }

    /** @test */
    public function it_sets_articles_from_array(): void
    {
        $pay = new Pay([
            'articles' => [
                ['identifier' => 'ART-001', 'description' => 'Test Article'],
            ],
        ]);

        $articles = $pay->articles();

        $this->assertIsArray($articles);
        $this->assertCount(1, $articles);
        $this->assertInstanceOf(ArticleAdapter::class, $articles[0]);
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
    public function it_converts_to_array(): void
    {
        $pay = new Pay([
            'merchantImageUrl' => 'https://test.com/img.png',
            'bankAccount' => 'NL20INGB0001234567',
        ]);

        $array = $pay->toArray();

        $this->assertIsArray($array);
        $this->assertSame('https://test.com/img.png', $array['merchantImageUrl']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $pay = new Pay([]);

        $array = $pay->toArray();
        $this->assertIsArray($array);
    }

    /** @test */
    public function it_sets_shipping_recipient_from_array(): void
    {
        $pay = new Pay([
            'billing' => [
                'recipient' => [
                    'category' => 'Person',
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ],
            ],
            'shipping' => [
                'recipient' => [
                    'category' => 'Person',
                    'firstName' => 'Jane',
                    'lastName' => 'Smith',
                ],
            ],
        ]);

        $shipping = $pay->shipping();

        $this->assertInstanceOf(Recipient::class, $shipping);
    }

    /** @test */
    public function it_returns_shipping_recipient_without_parameter(): void
    {
        $pay = new Pay([
            'billing' => [
                'recipient' => [
                    'category' => 'Person',
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ],
            ],
        ]);

        // When billing is set without shipping, shipping is auto-set from billing
        $shipping = $pay->shipping();

        $this->assertInstanceOf(Recipient::class, $shipping);
    }
}
