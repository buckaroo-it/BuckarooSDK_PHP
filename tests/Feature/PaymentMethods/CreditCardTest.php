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
class CreditCardTest extends TestCase
{
    /** @test */
    public function it_creates_a_visa_payment_with_redirect(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $redirectUrl = 'https://checkout.buckaroo.nl/redirect/3DSAuth/' . $transactionKey;

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 790, 'Description' => 'Waiting on user input'],
                    'SubCode' => ['Code' => 'S001', 'Description' => '3D Secure required'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => [
                    'Name' => 'Redirect',
                    'RedirectURL' => $redirectUrl,
                ],
                'Services' => [
                    [
                        'Name' => 'visa',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-123456',
                'Currency' => 'EUR',
                'AmountDebit' => 100.30,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('creditcard')->pay([
            'amountDebit' => 100.30,
            'invoice' => 'INV-123456',
            'currency' => 'EUR',
            'name' => 'visa',
        ]);

        $this->assertTrue($response->isWaitingOnUserInput());
        $this->assertTrue($response->hasRedirect());
        $this->assertEquals($redirectUrl, $response->getRedirectUrl());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
    }

    /** @test */
    public function it_creates_encrypted_payment(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 791, 'Description' => 'Pending processing'],
                    'SubCode' => ['Code' => 'S002', 'Description' => 'Processing'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'mastercard',
                        'Action' => 'PayEncrypted',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-123456',
                'Currency' => 'EUR',
                'AmountDebit' => 50.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('creditcard')->payEncrypted([
            'amountDebit' => 50.00,
            'invoice' => 'INV-123456',
            'currency' => 'EUR',
            'name' => 'mastercard',
            'encryptedCardData' => 'encrypted_data_here',
        ]);

        $this->assertTrue($response->isPendingProcessing());
        $this->assertFalse($response->hasRedirect());
    }

    /** @test */
    public function it_creates_payment_with_token(): void
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
                        'Name' => 'mastercard',
                        'Action' => 'PayWithToken',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-TOKEN-001',
                'Currency' => 'EUR',
                'AmountDebit' => 45.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('creditcard')->payWithToken([
            'amountDebit' => 45.00,
            'invoice' => 'INV-TOKEN-001',
            'currency' => 'EUR',
            'name' => 'mastercard',
            'sessionId' => 'SESSION_TOKEN_123',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_creates_payment_with_security_code(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 791, 'Description' => 'Pending processing'],
                    'SubCode' => ['Code' => 'S002', 'Description' => 'Processing'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'mastercard',
                        'Action' => 'PayWithSecurityCode',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-SEC-CODE-001',
                'Currency' => 'EUR',
                'AmountDebit' => 50.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('creditcard')->payWithSecurityCode([
            'amountDebit' => 50.00,
            'invoice' => 'INV-SEC-CODE-001',
            'currency' => 'EUR',
            'name' => 'mastercard',
            'originalTransactionKey' => 'ORIG_TX_KEY',
            'encryptedSecurityCode' => 'encrypted_cvv_here',
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /** @test */
    public function it_creates_recurrent_payment(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $originalTxKey = 'ORIG_TX_KEY_FOR_RECURRENT';

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
                        'Name' => 'mastercard',
                        'Action' => 'PayRecurrent',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-RECURRENT-001',
                'Currency' => 'EUR',
                'AmountDebit' => 29.99,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('creditcard')->payRecurrent([
            'amountDebit' => 29.99,
            'invoice' => 'INV-RECURRENT-001',
            'currency' => 'EUR',
            'name' => 'mastercard',
            'originalTransactionKey' => $originalTxKey,
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_creates_authorize(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $redirectUrl = 'https://checkout.buckaroo.nl/redirect/3DSAuth/' . $transactionKey;

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 790, 'Description' => 'Waiting on user input'],
                    'SubCode' => ['Code' => 'S001', 'Description' => '3D Secure required'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => [
                    'Name' => 'Redirect',
                    'RedirectURL' => $redirectUrl,
                ],
                'Services' => [
                    [
                        'Name' => 'mastercard',
                        'Action' => 'Authorize',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-AUTH-001',
                'Currency' => 'EUR',
                'AmountDebit' => 200.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('creditcard')->authorize([
            'amountDebit' => 200.00,
            'invoice' => 'INV-AUTH-001',
            'currency' => 'EUR',
            'name' => 'mastercard',
        ]);

        $this->assertTrue($response->isWaitingOnUserInput());
        $this->assertTrue($response->hasRedirect());
    }

    /** @test */
    public function it_creates_encrypted_authorize(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 791, 'Description' => 'Pending processing'],
                    'SubCode' => ['Code' => 'S002', 'Description' => 'Processing'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'mastercard',
                        'Action' => 'AuthorizeEncrypted',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-AUTH-ENC-001',
                'Currency' => 'EUR',
                'AmountDebit' => 75.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('creditcard')->authorizeEncrypted([
            'amountDebit' => 75.00,
            'invoice' => 'INV-AUTH-ENC-001',
            'currency' => 'EUR',
            'name' => 'mastercard',
            'encryptedCardData' => 'encrypted_card_data_here',
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /** @test */
    public function it_captures_authorized_payment(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $originalTxKey = 'ORIG_AUTH_TX_KEY';

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
                        'Name' => 'mastercard',
                        'Action' => 'Capture',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-CAPTURE-001',
                'Currency' => 'EUR',
                'AmountDebit' => 150.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('creditcard')->capture([
            'amountDebit' => 150.00,
            'invoice' => 'INV-CAPTURE-001',
            'currency' => 'EUR',
            'name' => 'mastercard',
            'originalTransactionKey' => $originalTxKey,
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_refunds_payment(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $originalTxKey = 'ORIG_PAY_TX_KEY';

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
                        'Name' => 'mastercard',
                        'Action' => 'Refund',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-REFUND-001',
                'Currency' => 'EUR',
                'AmountCredit' => 25.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('creditcard')->refund([
            'amountCredit' => 25.00,
            'invoice' => 'INV-REFUND-001',
            'name' => 'mastercard',
            'originalTransactionKey' => $originalTxKey,
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_cancels_authorized_payment(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $originalTxKey = 'ORIG_AUTH_TX_KEY';

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 791, 'Description' => 'Pending processing'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Cancel pending'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'mastercard',
                        'Action' => 'CancelAuthorize',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-CANCEL-001',
                'Currency' => 'EUR',
                'AmountCredit' => 100.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('creditcard')->cancelAuthorize([
            'amountCredit' => 100.00,
            'invoice' => 'INV-CANCEL-001',
            'name' => 'mastercard',
            'originalTransactionKey' => $originalTxKey,
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /** @test */
    public function it_extracts_service_parameters_from_response(): void
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
                        'Name' => 'visa',
                        'Action' => 'Pay',
                        'Parameters' => [
                            ['Name' => 'CardNumberEnding', 'Value' => '1234'],
                            ['Name' => 'CardExpirationDate', 'Value' => '2025-12'],
                        ],
                    ]
                ],
                'Invoice' => 'INV-PARAMS-001',
                'Currency' => 'EUR',
                'AmountDebit' => 100.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('creditcard')->pay([
            'amountDebit' => 100.00,
            'invoice' => 'INV-PARAMS-001',
            'currency' => 'EUR',
            'name' => 'visa',
        ]);

        $this->assertTrue($response->isSuccess());
        $params = $response->getServiceParameters();
        $this->assertEquals('1234', $params['cardnumberending']);
        $this->assertEquals('2025-12', $params['cardexpirationdate']);
    }

    /** @test */
    public function it_throws_exception_for_missing_card_name(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Missing creditcard name');

        $this->buckaroo->method('creditcard')->pay([
            'amountDebit' => 100.00,
            'invoice' => 'INV-001',
            'currency' => 'EUR',
        ]);
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
                        'Name' => 'visa',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-STATUS-001',
                'Currency' => 'EUR',
                'AmountDebit' => 10.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('creditcard')->pay([
            'amountDebit' => 10.00,
            'invoice' => 'INV-STATUS-001',
            'currency' => 'EUR',
            'name' => 'visa',
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

    /**
     * @test
     * @dataProvider cardTypeProvider
     */
    public function it_works_with_different_card_types(string $cardType): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $redirectUrl = "https://checkout.buckaroo.nl/redirect/3DSAuth/{$transactionKey}";

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 790, 'Description' => 'Waiting on user input'],
                    'SubCode' => ['Code' => 'S001', 'Description' => '3D Secure required'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => [
                    'Name' => 'Redirect',
                    'RedirectURL' => $redirectUrl,
                ],
                'Services' => [
                    [
                        'Name' => $cardType,
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-CARD-001',
                'Currency' => 'EUR',
                'AmountDebit' => 10.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('creditcard')->pay([
            'amountDebit' => 10.00,
            'invoice' => 'INV-CARD-001',
            'currency' => 'EUR',
            'name' => $cardType,
        ]);

        $this->assertTrue($response->isWaitingOnUserInput());
        $this->assertTrue($response->hasRedirect());
    }

    public static function cardTypeProvider(): array
    {
        return [
            ['visa'],
            ['mastercard'],
            ['amex'],
        ];
    }
}
