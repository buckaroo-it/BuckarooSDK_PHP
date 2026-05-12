<?php

declare(strict_types=1);

namespace Tests\Feature\PaymentMethods;

use Tests\FeatureTestCase;
use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class BankingTest extends FeatureTestCase
{
    /** @test */
    public function it_creates_a_banking_payment_order(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 794, 'Description' => 'Pending approval'],
                    'SubCode' => ['Code' => 'C000', 'Description' => 'Pending approval'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'Banking',
                        'Action' => null,
                        'Parameters' => [
                            ['Name' => 'Processed', 'Value' => 'Classic'],
                        ],
                    ],
                ],
                'AmountCredit' => 150.00,
                'IsTest' => true,
                'CustomerName' => 'John Doe',
            ]),
        ]);

        $response = $this->buckaroo->method('banking')->paymentOrder([
            'amountCredit' => 150.00,
            'invoice' => 'INV-BANKING-001',
            'description' => 'Banking PaymentOrder Test',
            'accountHolderName' => 'John Doe',
            'iban' => 'NL91ABNA0417164300',
            'processingDate' => '12/12/2026',
            'bic' => 'ABNANL2A',
            'purpose' => 'Testing',
        ]);

        $this->assertTrue($response->isPendingApproval());
        $this->assertEquals(150.00, $response->getAmountCredit());
        $this->assertSame($transactionKey, $response->getTransactionKey());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_a_banking_payment_order_with_minimal_parameters(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 794, 'Description' => 'Pending approval'],
                    'SubCode' => ['Code' => 'C000', 'Description' => 'Pending approval'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'Banking',
                        'Action' => null,
                        'Parameters' => [
                            ['Name' => 'Processed', 'Value' => 'Classic'],
                        ],
                    ],
                ],
                'Invoice' => 'INV-BANKING-002',
                'Currency' => 'EUR',
                'AmountCredit' => 100.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('banking')->paymentOrder([
            'amountCredit' => 100.00,
            'invoice' => 'INV-BANKING-002',
            'description' => 'Banking PaymentOrder Minimal Test',
            'accountHolderName' => 'Jane Smith',
            'iban' => 'NL44RABO0123456789',
        ]);

        $this->assertTrue($response->isPendingApproval());
        $this->assertEquals(100.00, $response->getAmountCredit());
        $this->assertTrue($response->get('IsTest'));
        $this->assertSame($transactionKey, $response->getTransactionKey());
    }

    /** @test */
    public function it_creates_a_banking_instant_payment_order(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 794, 'Description' => 'Pending approval'],
                    'SubCode' => ['Code' => 'C000', 'Description' => 'Pending approval'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'Banking',
                        'Action' => null,
                        'Parameters' => [
                            ['Name' => 'Processed', 'Value' => 'Instant'],
                        ],
                    ],
                ],
                'Invoice' => 'INV-BANKING-INSTANT-001',
                'Currency' => 'EUR',
                'AmountCredit' => 75.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('banking')->instantPaymentOrder([
            'amountCredit' => 75.00,
            'invoice' => 'INV-BANKING-INSTANT-001',
            'description' => 'Banking Instant PaymentOrder Test',
            'accountHolderName' => 'Bob Johnson',
            'iban' => 'NL91ABNA0417164300',
        ]);

        $this->assertTrue($response->isPendingApproval());
        $this->assertEquals(75.00, $response->getAmountCredit());
        $this->assertTrue($response->get('IsTest'));
        $this->assertSame($transactionKey, $response->getTransactionKey());
    }

    /** @test */
    public function it_creates_a_banking_payment_order_with_structured_reference(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 794, 'Description' => 'Pending approval'],
                    'SubCode' => ['Code' => 'C000', 'Description' => 'Pending approval'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'Banking',
                        'Action' => null,
                        'Parameters' => [
                            ['Name' => 'Processed', 'Value' => 'Instant'],
                        ],
                    ],
                ],
                'Invoice' => 'INV-BANKING-003',
                'Currency' => 'EUR',
                'AmountCredit' => 200.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('banking')->paymentOrder([
            'amountCredit' => 200.00,
            'invoice' => 'INV-BANKING-003',
            'description' => 'Banking PaymentOrder with Structured Reference',
            'accountHolderName' => 'Alice Brown',
            'iban' => 'NL91ABNA0417164300',
            'structuredIssuerType' => 'ISO',
            'structuredReference' => 'RF18539007547034',
        ]);

        $this->assertTrue($response->isPendingApproval());
        $this->assertEquals(200.00, $response->getAmountCredit());
        $this->assertTrue($response->get('IsTest'));
        $this->assertSame($transactionKey, $response->getTransactionKey());
    }
}
