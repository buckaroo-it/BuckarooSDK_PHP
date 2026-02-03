<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\iDeal;

use Buckaroo\PaymentMethods\iDeal\iDeal;
use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

class iDealTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->useMock();
    }

    /** @test */
    public function it_can_pay_with_ideal(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('ideal')->pay([
            'amountDebit' => 10.00,
            'invoice' => 'IDEAL-PAY-001',
            'issuer' => 'ABNANL2A',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_pay_fast_checkout(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('ideal')->payFastCheckout([
            'amountDebit' => 15.00,
            'invoice' => 'IDEAL-FAST-001',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_pay_remainder(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('ideal')->payRemainder([
            'amountDebit' => 5.00,
            'invoice' => 'IDEAL-REMAINDER-001',
            'originalTransactionKey' => 'ORIGINAL-TX-KEY',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_instant_refund(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('ideal')->instantRefund([
            'amountCredit' => 10.00,
            'invoice' => 'IDEAL-REFUND-001',
            'originalTransactionKey' => 'ORIGINAL-TX-KEY',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_get_issuers(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('GET', '*/Transaction/Specification/ideal*', [
                'Name' => 'ideal',
                'Description' => 'iDEAL',
                'Actions' => [
                    [
                        'Name' => 'Pay',
                        'RequestParameters' => [
                            [
                                'Name' => 'issuer',
                                'DataType' => 'List',
                                'Required' => true,
                                'ListItemDescriptions' => [
                                    ['Value' => 'ABNANL2A', 'Description' => 'ABN AMRO'],
                                    ['Value' => 'INGBNL2A', 'Description' => 'ING'],
                                    ['Value' => 'RABONL2U', 'Description' => 'Rabobank'],
                                ],
                            ],
                        ],
                    ],
                ],
            ]),
        ]);

        $issuers = $this->buckaroo->method('ideal')->issuers();

        $this->assertIsArray($issuers);
        $this->assertNotEmpty($issuers);
        $this->assertCount(3, $issuers);
        $this->assertSame('ABNANL2A', $issuers[0]['id']);
        $this->assertSame('ABN AMRO', $issuers[0]['name']);
    }

    /** @test */
    public function it_returns_self_when_manually_enabled_for_pay(): void
    {
        $payment = new iDeal($this->buckaroo->client(), 'ideal');
        $payment->manually(true);
        $payment->setPayload([
            'amountDebit' => 10.00,
            'invoice' => 'MANUAL-001',
            'issuer' => 'ABNANL2A',
        ]);

        $result = $payment->pay();

        $this->assertInstanceOf(iDeal::class, $result);
    }

    /** @test */
    public function it_returns_self_when_manually_enabled_for_pay_fast_checkout(): void
    {
        $payment = new iDeal($this->buckaroo->client(), 'ideal');
        $payment->manually(true);
        $payment->setPayload([
            'amountDebit' => 15.00,
            'invoice' => 'MANUAL-FAST-001',
        ]);

        $result = $payment->payFastCheckout();

        $this->assertInstanceOf(iDeal::class, $result);
    }
}
