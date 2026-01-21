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
class AfterpayDigiAcceptTest extends FeatureTestCase
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
                        'Name' => 'afterpaydigiaccept',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-DIGIACCEPT-001',
                'Currency' => 'EUR',
                'AmountDebit' => 199.99,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('afterpaydigiaccept')->pay([
            'amountDebit' => 199.99,
            'invoice' => 'INV-DIGIACCEPT-001',
            'currency' => 'EUR',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-DIGIACCEPT-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(199.99, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_pay_transaction_with_complete_billing_data(): void
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
                        'Name' => 'afterpaydigiaccept',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-DIGIACCEPT-FULL-001',
                'Currency' => 'EUR',
                'AmountDebit' => 149.50,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('afterpaydigiaccept')->pay([
            'amountDebit' => 149.50,
            'invoice' => 'INV-DIGIACCEPT-FULL-001',
            'currency' => 'EUR',
            'billing' => [
                'recipient' => [
                    'category' => 'Person',
                    'firstName' => 'Thomas',
                    'lastName' => 'Anderson',
                ],
                'address' => [
                    'street' => 'Prinsengracht',
                    'houseNumber' => '789',
                    'zipcode' => '1017JK',
                    'city' => 'Amsterdam',
                    'country' => 'NL',
                ],
                'phone' => [
                    'mobile' => '0612345678',
                ],
                'email' => 'thomas.anderson@example.com',
            ],
            'articles' => [
                [
                    'identifier' => 'PROD-500',
                    'description' => 'Gaming Console',
                    'quantity' => 1,
                    'price' => 149.50,
                    'vatPercentage' => 21,
                ],
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-DIGIACCEPT-FULL-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(149.50, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_authorize_transaction(): void
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
                        'Name' => 'afterpaydigiaccept',
                        'Action' => 'Authorize',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-DIGIACCEPT-AUTH-001',
                'Currency' => 'EUR',
                'AmountDebit' => 350.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('afterpaydigiaccept')->authorize([
            'amountDebit' => 350.00,
            'invoice' => 'INV-DIGIACCEPT-AUTH-001',
            'currency' => 'EUR',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-DIGIACCEPT-AUTH-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(350.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_captures_authorized_payment(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $originalTxKey = 'ORIG_DIGIACCEPT_AUTH_TX_KEY';

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Capture successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'afterpaydigiaccept',
                        'Action' => 'Capture',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-DIGIACCEPT-CAPTURE-001',
                'Currency' => 'EUR',
                'AmountDebit' => 350.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('afterpaydigiaccept')->capture([
            'amountDebit' => 350.00,
            'invoice' => 'INV-DIGIACCEPT-CAPTURE-001',
            'currency' => 'EUR',
            'originalTransactionKey' => $originalTxKey,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-DIGIACCEPT-CAPTURE-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(350.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_cancels_authorize_transaction(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $originalTxKey = 'ORIG_DIGIACCEPT_AUTH_TX_KEY';

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Cancellation successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => null,
                'Services' => [
                    [
                        'Name' => 'afterpaydigiaccept',
                        'Action' => 'CancelAuthorize',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-DIGIACCEPT-CANCEL-001',
                'Currency' => 'EUR',
                'AmountCredit' => 350.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('afterpaydigiaccept')->cancelAuthorize([
            'amountCredit' => 350.00,
            'invoice' => 'INV-DIGIACCEPT-CANCEL-001',
            'originalTransactionKey' => $originalTxKey,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-DIGIACCEPT-CANCEL-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(350.00, $response->getAmountCredit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_refunds_payment(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $originalTxKey = 'ORIG_DIGIACCEPT_PAY_TX_KEY';

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
                        'Name' => 'afterpaydigiaccept',
                        'Action' => 'Refund',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-DIGIACCEPT-REFUND-001',
                'Currency' => 'EUR',
                'AmountCredit' => 49.99,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('afterpaydigiaccept')->refund([
            'amountCredit' => 49.99,
            'invoice' => 'INV-DIGIACCEPT-REFUND-001',
            'originalTransactionKey' => $originalTxKey,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-DIGIACCEPT-REFUND-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(49.99, $response->getAmountCredit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_processes_payment_with_multiple_articles(): void
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
                        'Name' => 'afterpaydigiaccept',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-DIGIACCEPT-MULTI-001',
                'Currency' => 'EUR',
                'AmountDebit' => 425.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('afterpaydigiaccept')->pay([
            'amountDebit' => 425.00,
            'invoice' => 'INV-DIGIACCEPT-MULTI-001',
            'currency' => 'EUR',
            'articles' => [
                [
                    'identifier' => 'PROD-300',
                    'description' => 'Tablet',
                    'quantity' => 1,
                    'price' => 299.00,
                    'vatPercentage' => 21,
                ],
                [
                    'identifier' => 'PROD-301',
                    'description' => 'Tablet Case',
                    'quantity' => 1,
                    'price' => 49.00,
                    'vatPercentage' => 21,
                ],
                [
                    'identifier' => 'PROD-302',
                    'description' => 'Stylus Pen',
                    'quantity' => 1,
                    'price' => 77.00,
                    'vatPercentage' => 21,
                ],
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-DIGIACCEPT-MULTI-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(425.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
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
                        'Name' => 'afterpaydigiaccept',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-DIGIACCEPT-SHIPPING-001',
                'Currency' => 'EUR',
                'AmountDebit' => 225.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('afterpaydigiaccept')->pay([
            'amountDebit' => 225.00,
            'invoice' => 'INV-DIGIACCEPT-SHIPPING-001',
            'currency' => 'EUR',
            'billing' => [
                'recipient' => [
                    'category' => 'Person',
                    'firstName' => 'Alice',
                    'lastName' => 'Johnson',
                ],
                'address' => [
                    'street' => 'Billing Lane',
                    'houseNumber' => '15',
                    'zipcode' => '1100CC',
                    'city' => 'Amsterdam',
                    'country' => 'NL',
                ],
                'email' => 'alice.johnson@example.com',
            ],
            'shipping' => [
                'recipient' => [
                    'category' => 'Person',
                    'firstName' => 'Bob',
                    'lastName' => 'Johnson',
                ],
                'address' => [
                    'street' => 'Shipping Avenue',
                    'houseNumber' => '25',
                    'zipcode' => '3000DD',
                    'city' => 'Rotterdam',
                    'country' => 'NL',
                ],
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-DIGIACCEPT-SHIPPING-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(225.00, $response->getAmountDebit());
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
                        'Name' => 'afterpaydigiaccept',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-DIGIACCEPT-STATUS-001',
                'Currency' => 'EUR',
                'AmountDebit' => 10.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('afterpaydigiaccept')->pay([
            'amountDebit' => 10.00,
            'invoice' => 'INV-DIGIACCEPT-STATUS-001',
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
