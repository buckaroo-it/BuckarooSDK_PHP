<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Billink\Models;

use Buckaroo\PaymentMethods\Billink\Models\Pay;
use Buckaroo\PaymentMethods\Billink\Models\Recipient;
use Buckaroo\PaymentMethods\Billink\Service\ParameterKeys\ArticleAdapter;
use Tests\TestCase;

class PayTest extends TestCase
{
    /** @test */
    public function it_sets_trackandtrace(): void
    {
        $pay = new Pay(['trackandtrace' => 'TRACK123456']);

        $this->assertSame('TRACK123456', $pay->trackandtrace);
    }

    /** @test */
    public function it_sets_vat_number(): void
    {
        $pay = new Pay(['vATNumber' => 'NL123456789B01']);

        $this->assertSame('NL123456789B01', $pay->vATNumber);
    }

    /** @test */
    public function it_sets_billing_recipient_from_array(): void
    {
        $pay = new Pay([
            'billing' => [
                'recipient' => [
                    'category' => 'B2C',
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ],
            ],
        ]);

        $billing = $pay->billing();

        $this->assertInstanceOf(Recipient::class, $billing);
    }

    /** @test */
    public function it_sets_shipping_recipient_from_array(): void
    {
        $pay = new Pay([
            'billing' => [
                'recipient' => ['category' => 'B2C', 'firstName' => 'John', 'lastName' => 'Doe'],
            ],
            'shipping' => [
                'recipient' => ['category' => 'B2C', 'firstName' => 'Jane', 'lastName' => 'Doe'],
            ],
        ]);

        $shipping = $pay->shipping();

        $this->assertInstanceOf(Recipient::class, $shipping);
    }

    /** @test */
    public function it_sets_articles_from_array(): void
    {
        $pay = new Pay([
            'articles' => [
                ['identifier' => 'ART-001', 'description' => 'Test Article'],
                ['identifier' => 'ART-002', 'description' => 'Another Article'],
            ],
        ]);

        $articles = $pay->articles();

        $this->assertIsArray($articles);
        $this->assertCount(2, $articles);
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
    public function it_sets_all_properties_together(): void
    {
        $pay = new Pay([
            'trackandtrace' => 'TRACK999',
            'vATNumber' => 'NL987654321B99',
        ]);

        $this->assertSame('TRACK999', $pay->trackandtrace);
        $this->assertSame('NL987654321B99', $pay->vATNumber);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $pay = new Pay([
            'trackandtrace' => 'TEST-TRACK',
            'vATNumber' => 'VAT-TEST',
        ]);

        $array = $pay->toArray();

        $this->assertIsArray($array);
        $this->assertSame('TEST-TRACK', $array['trackandtrace']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $pay = new Pay([]);

        $array = $pay->toArray();
        $this->assertIsArray($array);
    }
}
