<?php

declare(strict_types=1);

namespace Tests\Feature\PaymentMethods;

use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class KlarnaKPTest extends TestCase
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
                        'Name' => 'klarnakp',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-KLARNA-001',
                'Currency' => 'EUR',
                'AmountDebit' => 150.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('klarnakp')->pay([
            'amountDebit' => 150.00,
            'invoice' => 'INV-KLARNA-001',
            'currency' => 'EUR',
            'operatingCountry' => 'NL',
            'pno' => '01011990',
            'gender' => 1,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-KLARNA-001', $response->getInvoice());
    }

    /** @test */
    public function it_creates_reserve_transaction(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Reservation successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'klarnakp',
                        'Action' => 'Reserve',
                        'Parameters' => [
                            ['Name' => 'ReservationNumber', 'Value' => 'RES-123456'],
                        ],
                    ]
                ],
                'Invoice' => 'INV-KLARNA-RESERVE-001',
                'Currency' => 'EUR',
                'AmountDebit' => 200.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('klarnakp')->reserve([
            'amountDebit' => 200.00,
            'invoice' => 'INV-KLARNA-RESERVE-001',
            'currency' => 'EUR',
            'operatingCountry' => 'NL',
            'pno' => '01011990',
            'gender' => 1,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-KLARNA-RESERVE-001', $response->getInvoice());
        $params = $response->getServiceParameters();
        $this->assertEquals('RES-123456', $params['reservationnumber']);
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
                        'Name' => 'klarnakp',
                        'Action' => 'CancelReservation',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-KLARNA-CANCEL-001',
                'Currency' => 'EUR',
                'AmountCredit' => 200.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('klarnakp')->cancelReserve([
            'amountCredit' => 200.00,
            'invoice' => 'INV-KLARNA-CANCEL-001',
            'reservationNumber' => 'RES-123456',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
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
                        'Name' => 'klarnakp',
                        'Action' => 'UpdateReservation',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-KLARNA-UPDATE-001',
                'Currency' => 'EUR',
                'AmountDebit' => 180.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('klarnakp')->updateReserve([
            'amountDebit' => 180.00,
            'invoice' => 'INV-KLARNA-UPDATE-001',
            'reservationNumber' => 'RES-123456',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
    }

    /** @test */
    public function it_refunds_payment(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $originalTxKey = 'ORIG_KLARNAKP_TX_KEY';

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
                        'Name' => 'klarnakp',
                        'Action' => 'Refund',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-KLARNA-REFUND-001',
                'Currency' => 'EUR',
                'AmountCredit' => 50.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('klarnakp')->refund([
            'amountCredit' => 50.00,
            'invoice' => 'INV-KLARNA-REFUND-001',
            'originalTransactionKey' => $originalTxKey,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
    }

    /** @test */
    public function it_processes_payment_with_articles_and_billing(): void
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
                        'Name' => 'klarnakp',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-KLARNA-FULL-001',
                'Currency' => 'EUR',
                'AmountDebit' => 125.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('klarnakp')->pay([
            'amountDebit' => 125.00,
            'invoice' => 'INV-KLARNA-FULL-001',
            'currency' => 'EUR',
            'operatingCountry' => 'NL',
            'pno' => '01011990',
            'gender' => 1,
            'billing' => [
                'recipient' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ],
                'address' => [
                    'street' => 'Main Street',
                    'houseNumber' => '123',
                    'zipcode' => '1234AB',
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
                        'Name' => 'klarnakp',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-KLARNA-STATUS-001',
                'Currency' => 'EUR',
                'AmountDebit' => 10.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('klarnakp')->pay([
            'amountDebit' => 10.00,
            'invoice' => 'INV-KLARNA-STATUS-001',
            'currency' => 'EUR',
            'operatingCountry' => 'NL',
            'pno' => '01011990',
            'gender' => 1,
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
            'waiting_on_user_input' => [790, 'isWaitingOnUserInput'],
            'pending_processing' => [791, 'isPendingProcessing'],
            'cancelled' => [890, 'isCanceled'],
        ];
    }
}
