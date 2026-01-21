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
class SEPATest extends FeatureTestCase
{
    /** @test */
    public function it_creates_a_sepa_direct_debit_payment(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Transaction successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'SepaDirectDebit',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-SEPA-001',
                'Currency' => 'EUR',
                'AmountDebit' => 100.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('sepadirectdebit')->pay([
            'amountDebit' => 100.00,
            'invoice' => 'INV-SEPA-001',
            'iban' => 'NL13TEST0123456789',
            'bic' => 'TESTNL2A',
            'mandateReference' => 'MANDATE-001',
            'mandateDate' => '2024-01-15',
            'collectDate' => '2024-01-20',
            'customer' => [
                'name' => 'John Doe',
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(100.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
        $this->assertSame($transactionKey, $response->getTransactionKey());
        $this->assertSame('INV-SEPA-001', $response->getInvoice());
    }

    /** @test */
    public function it_creates_a_sepa_authorize_transaction(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Authorization successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'SepaDirectDebit',
                        'Action' => 'Authorize',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-AUTH-001',
                'Currency' => 'EUR',
                'AmountDebit' => 75.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('sepadirectdebit')->authorize([
            'amountDebit' => 75.00,
            'invoice' => 'INV-AUTH-001',
            'iban' => 'NL13TEST0123456789',
            'bic' => 'TESTNL2A',
            'mandateReference' => 'MANDATE-002',
            'mandateDate' => '2024-01-15',
            'customer' => [
                'name' => 'Jane Smith',
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(75.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
        $this->assertSame($transactionKey, $response->getTransactionKey());
        $this->assertSame('INV-AUTH-001', $response->getInvoice());
    }

    /** @test */
    public function it_creates_a_sepa_recurrent_payment(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $originalTxKey = 'ORIG_SEPA_TX_KEY';

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Recurrent payment successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'SepaDirectDebit',
                        'Action' => 'PayRecurrent',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-RECUR-001',
                'Currency' => 'EUR',
                'AmountDebit' => 29.99,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('sepadirectdebit')->payRecurrent([
            'amountDebit' => 29.99,
            'invoice' => 'INV-RECUR-001',
            'originalTransactionKey' => $originalTxKey,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(29.99, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
        $this->assertSame($transactionKey, $response->getTransactionKey());
        $this->assertSame('INV-RECUR-001', $response->getInvoice());
    }

    /** @test */
    public function it_creates_a_sepa_payment_with_extra_info(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Transaction successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'SepaDirectDebit',
                        'Action' => 'Pay,ExtraInfo',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-EXTRA-001',
                'Currency' => 'EUR',
                'AmountDebit' => 150.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('sepadirectdebit')->extraInfo([
            'amountDebit' => 150.00,
            'invoice' => 'INV-EXTRA-001',
            'iban' => 'NL13TEST0123456789',
            'bic' => 'TESTNL2A',
            'mandateReference' => 'MANDATE-003',
            'mandateDate' => '2024-01-15',
            'customer' => [
                'name' => 'Bob Wilson',
                'initials' => 'B',
                'lastName' => 'Wilson',
                'birthDate' => '1985-06-15',
            ],
            'address' => [
                'street' => 'Main Street',
                'houseNumber' => '123',
                'zipcode' => '1234AB',
                'city' => 'Amsterdam',
                'country' => 'NL',
            ],
            'email' => 'bob@example.com',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(150.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
        $this->assertSame($transactionKey, $response->getTransactionKey());
        $this->assertSame('INV-EXTRA-001', $response->getInvoice());
    }

    /** @test */
    public function it_creates_a_sepa_payment_with_emandate(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'E-mandate payment successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'SepaDirectDebit',
                        'Action' => 'PayWithEmandate',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-EMANDATE-001',
                'Currency' => 'EUR',
                'AmountDebit' => 200.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('sepadirectdebit')->payWithEmandate([
            'amountDebit' => 200.00,
            'invoice' => 'INV-EMANDATE-001',
            'mandateReference' => 'EMANDATE-001',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(200.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
        $this->assertSame($transactionKey, $response->getTransactionKey());
        $this->assertSame('INV-EMANDATE-001', $response->getInvoice());
    }

    /** @test */
    public function it_creates_a_sepa_refund(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $originalTxKey = 'ORIG_SEPA_TX_KEY';

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Refund successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'SepaDirectDebit',
                        'Action' => 'Refund',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-REFUND-001',
                'Currency' => 'EUR',
                'AmountCredit' => 25.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('sepadirectdebit')->refund([
            'amountCredit' => 25.00,
            'invoice' => 'INV-REFUND-001',
            'originalTransactionKey' => $originalTxKey,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(25.00, $response->getAmountCredit());
        $this->assertTrue($response->get('IsTest'));
        $this->assertSame($transactionKey, $response->getTransactionKey());
        $this->assertSame('INV-REFUND-001', $response->getInvoice());
    }

    /**
     * @test
     * @dataProvider statusCodeProvider
     */
    public function it_handles_various_status_codes(int $statusCode, string $assertMethod): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => $statusCode, 'Description' => 'Status description'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Subcode description'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'SepaDirectDebit',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-STATUS-001',
                'Currency' => 'EUR',
                'AmountDebit' => 50.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('sepadirectdebit')->pay([
            'amountDebit' => 50.00,
            'invoice' => 'INV-STATUS-001',
            'iban' => 'NL13TEST0123456789',
            'customer' => [
                'name' => 'Test Customer',
            ],
        ]);

        if ($assertMethod === 'getStatusCode') {
            $this->assertEquals($statusCode, $response->getStatusCode());
        } else {
            $this->assertTrue($response->$assertMethod());
        }
    }

    public static function statusCodeProvider(): array
    {
        return [
            'success' => [190, 'isSuccess'],
            'failed' => [490, 'isFailed'],
            'validation_failure' => [491, 'isValidationFailure'],
            'rejected' => [690, 'isRejected'],
            'cancelled' => [890, 'isCanceled'],
            'technical_error' => [492, 'getStatusCode'],
            'waiting_on_consumer' => [792, 'getStatusCode'],
        ];
    }
}
