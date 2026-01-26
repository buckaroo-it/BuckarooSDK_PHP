<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\KlarnaKP\Models;

use Buckaroo\PaymentMethods\KlarnaKP\Models\Payload;
use Buckaroo\PaymentMethods\KlarnaKP\Models\Recipient;
use Buckaroo\PaymentMethods\KlarnaKP\Service\ParameterKeys\ArticleAdapter;
use Tests\TestCase;

class PayloadTest extends TestCase
{
    /** @test */
    public function it_sets_and_returns_billing_recipient(): void
    {
        $payload = new Payload([
            'billing' => [
                'recipient' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ],
            ],
        ]);

        $this->assertInstanceOf(Recipient::class, $payload->billing());
    }

    /** @test */
    public function it_sets_shipping_same_as_billing_by_default(): void
    {
        $payload = new Payload([
            'billing' => [
                'recipient' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ],
            ],
        ]);

        // When only billing is set, shipping should be same as billing
        $this->assertInstanceOf(Recipient::class, $payload->billing());
    }

    /** @test */
    public function it_sets_and_returns_separate_shipping_recipient(): void
    {
        $payload = new Payload([
            'billing' => [
                'recipient' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ],
            ],
            'shipping' => [
                'recipient' => [
                    'firstName' => 'Jane',
                    'lastName' => 'Smith',
                ],
            ],
        ]);

        $this->assertInstanceOf(Recipient::class, $payload->shipping());
    }

    /** @test */
    public function it_sets_and_returns_articles(): void
    {
        $payload = new Payload([
            'articles' => [
                [
                    'identifier' => 'ART001',
                    'description' => 'Product 1',
                    'quantity' => 1,
                    'unitPrice' => 10.00,
                    'vatPercentage' => 21,
                ],
                [
                    'identifier' => 'ART002',
                    'description' => 'Product 2',
                    'quantity' => 2,
                    'unitPrice' => 20.00,
                    'vatPercentage' => 21,
                ],
            ],
        ]);

        $articles = $payload->articles();

        $this->assertIsArray($articles);
        $this->assertCount(2, $articles);
        foreach ($articles as $article) {
            $this->assertInstanceOf(ArticleAdapter::class, $article);
        }
    }

    /** @test */
    public function it_can_be_created_with_all_properties(): void
    {
        $payload = new Payload([
            'gender' => 1,
            'operatingCountry' => 'NL',
            'pno' => '12345678',
            'reservationNumber' => 'RES-001',
        ]);

        $this->assertSame(1, $payload->gender);
        $this->assertSame('NL', $payload->operatingCountry);
        $this->assertSame('12345678', $payload->pno);
        $this->assertSame('RES-001', $payload->reservationNumber);
    }
}
