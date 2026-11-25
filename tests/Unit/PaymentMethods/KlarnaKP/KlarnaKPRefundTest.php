<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\KlarnaKP;

use Buckaroo\PaymentMethods\KlarnaKP\Models\Refund;
use Buckaroo\PaymentMethods\PaymentMethodFactory;
use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

class KlarnaKPRefundTest extends TestCase
{
    private const METHOD = 'klarnakp';

    protected function setUp(): void
    {
        parent::setUp();
        $this->useMock();
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);
    }

    /** @test */
    public function it_sends_article_groups_on_refund(): void
    {
        $response = $this->buckaroo->method(self::METHOD)->refund([
            'amountCredit' => 532.00,
            'invoice' => 'INV-REFUND-001',
            'originalTransactionKey' => 'ORIGINAL-TX-KEY',
            'articles' => [
                ['identifier' => 'SKU-001', 'quantity' => 2],
                ['identifier' => 'SKU-002', 'quantity' => 1],
            ],
        ]);

        $this->assertTrue($response->isSuccess());

        $body = $this->lastRequestBody();
        $service = $body['Services']['ServiceList'][0];

        $this->assertSame(self::METHOD, $service['name']);
        $this->assertSame('Refund', $service['action']);
        $this->assertSame(532.00, (float) $body['AmountCredit']);
        $this->assertSame('ORIGINAL-TX-KEY', $body['OriginalTransactionKey']);
        $this->assertEqualsCanonicalizing([
            ['Name' => 'ArticleNumber', 'GroupType' => 'Article', 'GroupID' => 1, 'Value' => 'SKU-001'],
            ['Name' => 'ArticleQuantity', 'GroupType' => 'Article', 'GroupID' => 1, 'Value' => 2],
            ['Name' => 'ArticleNumber', 'GroupType' => 'Article', 'GroupID' => 2, 'Value' => 'SKU-002'],
            ['Name' => 'ArticleQuantity', 'GroupType' => 'Article', 'GroupID' => 2, 'Value' => 1],
        ], $this->normalizeParameters($service['parameters']));
    }

    /** @test */
    public function it_only_sends_article_keys_present_in_the_payload(): void
    {
        $this->buckaroo->method(self::METHOD)->refund([
            'amountCredit' => 30.25,
            'invoice' => 'INV-REFUND-002',
            'originalTransactionKey' => 'ORIGINAL-TX-KEY',
            'articles' => [
                ['identifier' => 'SKU-001', 'quantity' => 1, 'price' => 30.25, 'vatPercentage' => 21],
            ],
        ]);

        $parameters = $this->lastRequestBody()['Services']['ServiceList'][0]['parameters'];

        $this->assertEqualsCanonicalizing(
            ['ArticleNumber', 'ArticleQuantity', 'ArticlePrice', 'ArticleVat'],
            array_column($parameters, 'Name')
        );
    }

    /**
     * @test
     * @dataProvider amountOnlyPayloadProvider
     */
    public function it_keeps_amount_only_refunds_unchanged(array $extra): void
    {
        $response = $this->buckaroo->method(self::METHOD)->refund(array_merge([
            'amountCredit' => 10.00,
            'invoice' => 'INV-REFUND-003',
            'originalTransactionKey' => 'ORIGINAL-TX-KEY',
        ], $extra));

        $this->assertTrue($response->isSuccess());

        $body = $this->lastRequestBody();

        $this->assertSame([
            'version' => 0,
            'action' => 'Refund',
            'name' => self::METHOD,
            'parameters' => [],
        ], $body['Services']['ServiceList'][0]);
        $this->assertSame(10.00, (float) $body['AmountCredit']);
        $this->assertSame('ORIGINAL-TX-KEY', $body['OriginalTransactionKey']);
    }

    public static function amountOnlyPayloadProvider(): array
    {
        return [
            'no articles key' => [[]],
            'empty articles' => [['articles' => []]],
        ];
    }

    /** @test */
    public function it_uses_an_explicitly_passed_model(): void
    {
        PaymentMethodFactory::get($this->buckaroo->client(), self::METHOD)
            ->setPayload([
                'amountCredit' => 5.00,
                'invoice' => 'INV-REFUND-004',
                'originalTransactionKey' => 'ORIGINAL-TX-KEY',
                'articles' => [['identifier' => 'IGNORED', 'quantity' => 9]],
            ])
            ->refund(new Refund(['articles' => [['identifier' => 'SKU-EXPLICIT', 'quantity' => 1]]]));

        $parameters = $this->lastRequestBody()['Services']['ServiceList'][0]['parameters'];

        $this->assertSame(['SKU-EXPLICIT'], array_values(array_column(
            array_filter($parameters, fn ($p) => $p['Name'] === 'ArticleNumber'),
            'Value'
        )));
    }

    private function lastRequestBody(): array
    {
        return json_decode((string) $this->mockBuckaroo->getLastRequestBody(), true);
    }

    private function normalizeParameters(array $parameters): array
    {
        return array_map(fn ($p) => [
            'Name' => $p['Name'],
            'GroupType' => $p['GroupType'],
            'GroupID' => (int) $p['GroupID'],
            'Value' => $p['Value'],
        ], $parameters);
    }
}
