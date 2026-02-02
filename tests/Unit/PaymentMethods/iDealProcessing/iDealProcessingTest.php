<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\iDealProcessing;

use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

class iDealProcessingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->useMock();
    }

    /** @test */
    public function it_can_pay(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('idealprocessing')->pay([
            'amountDebit' => 15.00,
            'invoice' => 'IDEALPROC-PAY-001',
            'issuer' => 'ABNANL2A',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_pay_remainder(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('idealprocessing')->payRemainder([
            'amountDebit' => 5.00,
            'invoice' => 'IDEALPROC-REMAINDER-001',
            'originalTransactionKey' => 'ORIGINAL-TX-KEY',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_get_issuers(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('GET', '*/Transaction/Specification/idealprocessing*', [
                'Name' => 'idealprocessing',
                'Description' => 'iDEAL Processing',
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
                                ],
                            ],
                        ],
                    ],
                ],
            ]),
        ]);

        $issuers = $this->buckaroo->method('idealprocessing')->issuers();

        $this->assertIsArray($issuers);
        $this->assertCount(2, $issuers);
    }

    /** @test */
    public function it_can_refund(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction/', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('idealprocessing')->refund([
            'amountCredit' => 15.00,
            'invoice' => 'IDEALPROC-REFUND-001',
            'originalTransactionKey' => 'ORIGINAL-TX-KEY',
        ]);

        $this->assertTrue($response->isSuccess());
    }
}
