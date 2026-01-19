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
class iDealQRTest extends TestCase
{
    /** @test */
    public function it_generates_an_ideal_qr_code(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'QR code generated'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'idealqr',
                        'Action' => 'Generate',
                        'Parameters' => [
                            ['Name' => 'Amount', 'Value' => 25.00],
                            ['Name' => 'PurchaseId', 'Value' => 'PURCHASE-001'],
                            ['Name' => 'Description', 'Value' => 'QR code payment'],
                        ],
                    ]
                ],
                'Invoice' => 'INV-QR-001',
                'Currency' => 'EUR',
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('ideal_qr')->generate([
            'amount' => 25.00,
            'purchaseId' => 'PURCHASE-001',
            'description' => 'QR code payment',
            'invoice' => 'INV-QR-001',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-QR-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_generates_qr_code_with_changeable_amount(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'QR code generated'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'idealqr',
                        'Action' => 'Generate',
                        'Parameters' => [
                            ['Name' => 'Amount', 'Value' => 50.00],
                            ['Name' => 'AmountIsChangeable', 'Value' => true],
                            ['Name' => 'MinAmount', 'Value' => 10.00],
                            ['Name' => 'MaxAmount', 'Value' => 100.00],
                        ],
                    ]
                ],
                'Invoice' => 'INV-QR-002',
                'Currency' => 'EUR',
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('ideal_qr')->generate([
            'amount' => 50.00,
            'amountIsChangeable' => true,
            'minAmount' => 10.00,
            'maxAmount' => 100.00,
            'invoice' => 'INV-QR-002',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-QR-002', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_generates_one_off_qr_code(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'One-off QR code generated'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'idealqr',
                        'Action' => 'Generate',
                        'Parameters' => [
                            ['Name' => 'Amount', 'Value' => 15.00],
                            ['Name' => 'IsOneOff', 'Value' => true],
                        ],
                    ]
                ],
                'Invoice' => 'INV-QR-003',
                'Currency' => 'EUR',
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('ideal_qr')->generate([
            'amount' => 15.00,
            'isOneOff' => true,
            'invoice' => 'INV-QR-003',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals('INV-QR-003', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertTrue($response->get('IsTest'));
        $this->assertEquals($transactionKey, $response->getTransactionKey());
    }

    /** @test */
    public function it_generates_qr_code_with_expiration(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $expiration = date('Y-m-d\TH:i:s', strtotime('+1 hour'));

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'QR code with expiration'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'idealqr',
                        'Action' => 'Generate',
                        'Parameters' => [
                            ['Name' => 'Amount', 'Value' => 30.00],
                            ['Name' => 'Expiration', 'Value' => $expiration],
                        ],
                    ]
                ],
                'Invoice' => 'INV-QR-004',
                'Currency' => 'EUR',
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('ideal_qr')->generate([
            'amount' => 30.00,
            'expiration' => $expiration,
            'invoice' => 'INV-QR-004',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals('INV-QR-004', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertTrue($response->get('IsTest'));
        $this->assertEquals($transactionKey, $response->getTransactionKey());
    }

    /** @test */
    public function it_generates_qr_code_with_custom_image_size(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'QR code generated'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'idealqr',
                        'Action' => 'Generate',
                        'Parameters' => [
                            ['Name' => 'Amount', 'Value' => 20.00],
                            ['Name' => 'ImageSize', 'Value' => 500],
                        ],
                    ]
                ],
                'Invoice' => 'INV-QR-005',
                'Currency' => 'EUR',
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('ideal_qr')->generate([
            'amount' => 20.00,
            'imageSize' => 500,
            'invoice' => 'INV-QR-005',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals('INV-QR-005', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertTrue($response->get('IsTest'));
        $this->assertEquals($transactionKey, $response->getTransactionKey());
    }

    /** @test */
    public function it_generates_processing_qr_code(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Processing QR code generated'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'idealqr',
                        'Action' => 'Generate',
                        'Parameters' => [
                            ['Name' => 'Amount', 'Value' => 40.00],
                            ['Name' => 'IsProcessing', 'Value' => true],
                        ],
                    ]
                ],
                'Invoice' => 'INV-QR-006',
                'Currency' => 'EUR',
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('ideal_qr')->generate([
            'amount' => 40.00,
            'isProcessing' => true,
            'invoice' => 'INV-QR-006',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals('INV-QR-006', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertTrue($response->get('IsTest'));
        $this->assertEquals($transactionKey, $response->getTransactionKey());
    }

    /**
     * @test
     * @dataProvider statusCodeProvider
     */
    public function it_handles_various_status_codes(int $statusCode, string $assertMethod): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => $statusCode, 'Description' => 'Status'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Sub status'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'idealqr',
                        'Action' => 'Generate',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-STATUS-001',
                'Currency' => 'EUR',
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('ideal_qr')->generate([
            'amount' => 10.00,
            'invoice' => 'INV-STATUS-001',
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
