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
class KlarnaTest extends FeatureTestCase
{
    /** @test */
    public function it_creates_pay_transaction(): void
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
                        'Name' => 'Klarna',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-KLARNA-001',
                'Currency' => 'EUR',
                'AmountDebit' => 150.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('klarna')->pay([
            'amountDebit' => 150.00,
            'invoice' => 'INV-KLARNA-001',
            'currency' => 'EUR',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-KLARNA-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(150.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_reserve_transaction(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 791, 'Description' => 'Pending'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Reservation pending'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'Klarna',
                        'Action' => 'Reserve',
                        'Parameters' => [
                            ['Name' => 'ReservationNumber', 'Value' => 'RES-KLARNA-123456'],
                        ],
                    ],
                ],
                'Invoice' => 'INV-KLARNA-RESERVE-001',
                'Currency' => 'EUR',
                'AmountDebit' => 200.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('klarna')->reserve([
            'amountDebit' => 200.00,
            'invoice' => 'INV-KLARNA-RESERVE-001',
            'currency' => 'EUR',
        ]);

        $this->assertTrue($response->isPendingProcessing());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-KLARNA-RESERVE-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(200.00, $response->getAmountDebit());
        $params = $response->getServiceParameters();
        $this->assertEquals('RES-KLARNA-123456', $params['reservationnumber']);
    }

    /** @test */
    public function it_cancels_reservation(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Cancellation successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'Klarna',
                        'Action' => 'CancelReservation',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-KLARNA-CANCEL-001',
                'Currency' => 'EUR',
                'AmountCredit' => 200.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('klarna')->cancelReserve([
            'amountCredit' => 200.00,
            'invoice' => 'INV-KLARNA-CANCEL-001',
            'reservationNumber' => 'RES-KLARNA-123456',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-KLARNA-CANCEL-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(200.00, $response->getAmountCredit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_extends_reservation(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Extension successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'Klarna',
                        'Action' => 'ExtendReservation',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-KLARNA-EXTEND-001',
                'Currency' => 'EUR',
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('klarna')->extendReserve([
            'invoice' => 'INV-KLARNA-EXTEND-001',
            'reservationNumber' => 'RES-KLARNA-123456',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-KLARNA-EXTEND-001', $response->getInvoice());
    }

    /** @test */
    public function it_updates_reservation(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Update successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'Klarna',
                        'Action' => 'UpdateReservation',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-KLARNA-UPDATE-001',
                'Currency' => 'EUR',
                'AmountDebit' => 175.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('klarna')->updateReserve([
            'amountDebit' => 175.00,
            'invoice' => 'INV-KLARNA-UPDATE-001',
            'reservationNumber' => 'RES-KLARNA-123456',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-KLARNA-UPDATE-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(175.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_pay_remainder_transaction(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $originalTxKey = 'ORIG_KLARNA_TX_KEY';

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Remainder payment successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'Klarna',
                        'Action' => 'PayRemainder',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-KLARNA-REM-001',
                'Currency' => 'EUR',
                'AmountDebit' => 75.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('klarna')->payRemainder([
            'amountDebit' => 75.00,
            'invoice' => 'INV-KLARNA-REM-001',
            'originalTransactionKey' => $originalTxKey,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-KLARNA-REM-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(75.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_refunds_payment(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $originalTxKey = 'ORIG_KLARNA_TX_KEY';

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
                        'Name' => 'Klarna',
                        'Action' => 'Refund',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-KLARNA-REFUND-001',
                'Currency' => 'EUR',
                'AmountCredit' => 50.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('klarna')->refund([
            'amountCredit' => 50.00,
            'invoice' => 'INV-KLARNA-REFUND-001',
            'originalTransactionKey' => $originalTxKey,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-KLARNA-REFUND-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(50.00, $response->getAmountCredit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_processes_payment_with_complete_billing_data(): void
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
                        'Name' => 'Klarna',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-KLARNA-FULL-001',
                'Currency' => 'EUR',
                'AmountDebit' => 125.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('klarna')->pay([
            'amountDebit' => 125.00,
            'invoice' => 'INV-KLARNA-FULL-001',
            'currency' => 'EUR',
            'billing' => [
                'recipient' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ],
                'address' => [
                    'street' => 'Keizersgracht',
                    'houseNumber' => '123',
                    'zipcode' => '1016DK',
                    'city' => 'Amsterdam',
                    'country' => 'NL',
                ],
                'phone' => [
                    'mobile' => '0612345678',
                ],
                'email' => 'john.doe@example.com',
            ],
            'articles' => [
                [
                    'identifier' => 'PROD-001',
                    'description' => 'Test Product',
                    'quantity' => 2,
                    'price' => 50.00,
                    'vatPercentage' => 21,
                ],
                [
                    'identifier' => 'PROD-002',
                    'description' => 'Another Product',
                    'quantity' => 1,
                    'price' => 25.00,
                    'vatPercentage' => 21,
                ],
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-KLARNA-FULL-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(125.00, $response->getAmountDebit());
    }

    /** @test */
    public function it_processes_payment_with_separate_shipping_address(): void
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
                        'Name' => 'Klarna',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-KLARNA-SHIP-001',
                'Currency' => 'EUR',
                'AmountDebit' => 100.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('klarna')->pay([
            'amountDebit' => 100.00,
            'invoice' => 'INV-KLARNA-SHIP-001',
            'currency' => 'EUR',
            'billing' => [
                'recipient' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ],
                'address' => [
                    'street' => 'Keizersgracht',
                    'houseNumber' => '123',
                    'zipcode' => '1016DK',
                    'city' => 'Amsterdam',
                    'country' => 'NL',
                ],
            ],
            'shipping' => [
                'recipient' => [
                    'firstName' => 'Jane',
                    'lastName' => 'Smith',
                ],
                'address' => [
                    'street' => 'Westerstraat',
                    'houseNumber' => '456',
                    'zipcode' => '3016DK',
                    'city' => 'Rotterdam',
                    'country' => 'NL',
                ],
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-KLARNA-SHIP-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(100.00, $response->getAmountDebit());
    }

    /** @test */
    public function it_processes_reservation_with_articles(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 791, 'Description' => 'Pending'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Reservation pending'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'Klarna',
                        'Action' => 'Reserve',
                        'Parameters' => [
                            ['Name' => 'ReservationNumber', 'Value' => 'RES-KLARNA-789'],
                        ],
                    ],
                ],
                'Invoice' => 'INV-KLARNA-RES-ART-001',
                'Currency' => 'EUR',
                'AmountDebit' => 300.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('klarna')->reserve([
            'amountDebit' => 300.00,
            'invoice' => 'INV-KLARNA-RES-ART-001',
            'currency' => 'EUR',
            'articles' => [
                [
                    'identifier' => 'ART-001',
                    'description' => 'Premium Product',
                    'quantity' => 3,
                    'price' => 100.00,
                    'vatPercentage' => 21,
                ],
            ],
        ]);

        $this->assertTrue($response->isPendingProcessing());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-KLARNA-RES-ART-001', $response->getInvoice());
        $params = $response->getServiceParameters();
        $this->assertEquals('RES-KLARNA-789', $params['reservationnumber']);
    }

    /** @test */
    public function it_processes_payment_with_shipping_info(): void
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
                        'Name' => 'Klarna',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-KLARNA-SHIPINFO-001',
                'Currency' => 'EUR',
                'AmountDebit' => 200.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('klarna')->pay([
            'amountDebit' => 200.00,
            'invoice' => 'INV-KLARNA-SHIPINFO-001',
            'currency' => 'EUR',
            'billing' => [
                'recipient' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ],
                'address' => [
                    'street' => 'Hoofdstraat',
                    'houseNumber' => '123',
                    'zipcode' => '1234AB',
                    'city' => 'Amsterdam',
                    'country' => 'NL',
                ],
                'email' => 'john.doe@example.com',
            ],
            'shippingInfo' => [
                'company' => 'DHL Express',
                'trackingNumber' => 'TRACK-123456789',
                'shippingMethod' => 'Next Day Delivery',
            ],
            'articles' => [
                [
                    'identifier' => 'SKU-001',
                    'description' => 'Shipped Product',
                    'quantity' => 2,
                    'price' => 100.00,
                    'vatPercentage' => 21,
                ],
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-KLARNA-SHIPINFO-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(200.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
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
                    'Code' => ['Code' => $statusCode, 'Description' => 'Status'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Sub status'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'Klarna',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-KLARNA-STATUS-001',
                'Currency' => 'EUR',
                'AmountDebit' => 10.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('klarna')->pay([
            'amountDebit' => 10.00,
            'invoice' => 'INV-KLARNA-STATUS-001',
            'currency' => 'EUR',
        ]);

        $this->assertTrue($response->$assertMethod());
    }

    public static function statusCodeProvider(): array
    {
        return [
            'success' => [190, 'isSuccess'],
            'failed' => [490, 'isFailed'],
            'validation_failure' => [491, 'isValidationFailure'],
            'rejected' => [690, 'isRejected'],
            'waiting_on_user_input' => [790, 'isWaitingOnUserInput'],
            'pending_processing' => [791, 'isPendingProcessing'],
            'cancelled' => [890, 'isCanceled'],
        ];
    }
}
