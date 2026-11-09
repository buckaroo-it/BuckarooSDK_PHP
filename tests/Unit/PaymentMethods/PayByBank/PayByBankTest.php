<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\PayByBank;

use Buckaroo\PaymentMethods\PayByBank\Models\Pay;
use Buckaroo\PaymentMethods\PayByBank\PayByBank;
use Buckaroo\PaymentMethods\PaymentInitiation\Models\Pay as PaymentInitiationPay;
use Buckaroo\PaymentMethods\PaymentInitiation\PaymentInitiation;
use Buckaroo\PaymentMethods\PaymentMethodFactory;
use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

class PayByBankTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->useMock();
    }

    public static function aliasProvider(): array
    {
        return [
            'paybybank' => ['paybybank'],
            'paymentinitiation' => ['paymentinitiation'],
        ];
    }

    /**
     * @test
     * @dataProvider aliasProvider
     */
    public function it_resolves_alias_to_pay_by_bank(string $alias): void
    {
        $paymentMethod = PaymentMethodFactory::get($this->buckaroo->client(), $alias);

        $this->assertInstanceOf(PayByBank::class, $paymentMethod);
        $this->assertSame('PayByBank', $paymentMethod->paymentName());
    }

    /** @test */
    public function it_resolves_paybybank_alias_to_pay_by_bank_class(): void
    {
        $paymentMethod = PaymentMethodFactory::get($this->buckaroo->client(), 'paybybank');

        $this->assertSame(PayByBank::class, get_class($paymentMethod));
    }

    /** @test */
    public function it_keeps_returning_payment_initiation_for_the_old_alias(): void
    {
        $paymentMethod = PaymentMethodFactory::get($this->buckaroo->client(), 'paymentinitiation');

        $this->assertInstanceOf(PaymentInitiation::class, $paymentMethod);
    }

    /**
     * @test
     * @dataProvider aliasProvider
     */
    public function it_can_pay(string $alias): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method($alias)->pay([
            'amountDebit' => 10.00,
            'invoice' => 'PBB-PAY-001',
            'issuer' => 'ABNANL2A',
            'countryCode' => 'NL',
        ]);

        $this->assertTrue($response->isSuccess());

        $service = $this->lastRequestService();
        $this->assertSame('PayByBank', $service['name']);
        $this->assertSame('Pay', $service['action']);
        $this->assertSame(
            ['Issuer' => 'ABNANL2A', 'CountryCode' => 'NL'],
            array_column($service['parameters'], 'Value', 'Name')
        );
    }

    /** @test */
    public function it_sends_belgian_issuer_with_country_code_be(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $this->buckaroo->method('paybybank')->pay([
            'amountDebit' => 10.00,
            'invoice' => 'PBB-PAY-BE-001',
            'issuer' => 'GEBABEBB',
            'countryCode' => 'BE',
        ]);

        $this->assertSame(
            ['Issuer' => 'GEBABEBB', 'CountryCode' => 'BE'],
            array_column($this->lastRequestService()['parameters'], 'Value', 'Name')
        );
    }

    /**
     * @test
     * @dataProvider aliasProvider
     */
    public function it_can_refund(string $alias): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method($alias)->refund([
            'amountCredit' => 10.00,
            'invoice' => 'PBB-REFUND-001',
            'originalTransactionKey' => 'ORIGINAL-TX-KEY',
        ]);

        $this->assertTrue($response->isSuccess());

        $service = $this->lastRequestService();
        $this->assertSame('PayByBank', $service['name']);
        $this->assertSame('Refund', $service['action']);
    }

    /**
     * @test
     * @dataProvider aliasProvider
     */
    public function it_can_get_issuers(string $alias): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('GET', '*/Transaction/Specification/PayByBank*', [
                'Name' => 'PayByBank',
                'Actions' => [
                    [
                        'Name' => 'Pay',
                        'RequestParameters' => [
                            [
                                'Name' => 'issuer',
                                'DataType' => 'List',
                                'ListItemDescriptions' => [
                                    ['Value' => 'ABNANL2A', 'Description' => 'ABN AMRO'],
                                    ['Value' => 'KREDBEBB', 'Description' => 'KBC'],
                                ],
                            ],
                        ],
                    ],
                ],
            ]),
        ]);

        $issuers = $this->buckaroo->method($alias)->issuers();

        $this->assertSame([
            ['id' => 'ABNANL2A', 'name' => 'ABN AMRO'],
            ['id' => 'KREDBEBB', 'name' => 'KBC'],
        ], $issuers);
    }

    /** @test */
    public function deprecated_payment_initiation_class_still_works(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $paymentMethod = new PaymentInitiation($this->buckaroo->client(), 'paymentinitiation');

        $this->assertInstanceOf(PayByBank::class, $paymentMethod);
        $this->assertSame('PayByBank', $paymentMethod->paymentName());

        $response = $paymentMethod->setPayload([
            'amountDebit' => 10.00,
            'invoice' => 'PBB-LEGACY-001',
            'issuer' => 'ABNANL2A',
        ])->pay();

        $this->assertTrue($response->isSuccess());
        $this->assertSame('PayByBank', $this->lastRequestService()['name']);
    }

    /** @test */
    public function deprecated_payment_initiation_pay_model_still_works(): void
    {
        $pay = new PaymentInitiationPay(['issuer' => 'ABNANL2A', 'countryCode' => 'BE']);

        $this->assertInstanceOf(Pay::class, $pay);
        $this->assertSame('ABNANL2A', $pay->issuer);
        $this->assertSame('BE', $pay->countryCode);
    }

    private function lastRequestService(): array
    {
        $body = json_decode((string) $this->mockBuckaroo->getLastRequestBody(), true);

        return $body['Services']['ServiceList'][0];
    }
}
