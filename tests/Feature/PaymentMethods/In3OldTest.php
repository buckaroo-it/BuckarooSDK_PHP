<?php

declare(strict_types=1);

namespace Tests\Feature\PaymentMethods;

use Tests\FeatureTestCase;
use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;

/**
 * Tests for In3Old (Capayable) payment method
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class In3OldTest extends FeatureTestCase
{
    /** @test */
    public function it_creates_an_in3old_payment(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $redirectUrl = 'https://capayable.nl/redirect/' . $transactionKey;

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 791, 'Description' => 'Pending processing'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Redirecting to Capayable'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => [
                    'Name' => 'Redirect',
                    'RedirectURL' => $redirectUrl,
                ],
                'Services' => [
                    [
                        'Name' => 'Capayable',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-IN3OLD-001',
                'Currency' => 'EUR',
                'AmountDebit' => 100.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('in3old')->pay([
            'amountDebit' => 100.00,
            'invoice' => 'INV-IN3OLD-001',
            'billing' => [
                'recipient' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'birthDate' => '1990-01-01',
                ],
                'address' => [
                    'street' => 'Test Street',
                    'houseNumber' => '1',
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
                    'identifier' => 'ART001',
                    'description' => 'Test Article',
                    'quantity' => 1,
                    'price' => 100.00,
                ],
            ],
        ]);

        $this->assertTrue($response->isPendingProcessing());
        $this->assertTrue($response->hasRedirect());
        $this->assertEquals($redirectUrl, $response->getRedirectUrl());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-IN3OLD-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(100.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_an_in3old_pay_in_installments(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $redirectUrl = 'https://capayable.nl/redirect/' . $transactionKey;

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 791, 'Description' => 'Pending processing'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Redirecting to Capayable'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => [
                    'Name' => 'Redirect',
                    'RedirectURL' => $redirectUrl,
                ],
                'Services' => [
                    [
                        'Name' => 'Capayable',
                        'Action' => 'PayInInstallments',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-IN3OLD-INSTALL-001',
                'Currency' => 'EUR',
                'AmountDebit' => 500.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('in3old')->payInInstallments([
            'amountDebit' => 500.00,
            'invoice' => 'INV-IN3OLD-INSTALL-001',
            'billing' => [
                'recipient' => [
                    'firstName' => 'Jane',
                    'lastName' => 'Smith',
                    'birthDate' => '1985-06-15',
                ],
                'address' => [
                    'street' => 'Main Street',
                    'houseNumber' => '123',
                    'zipcode' => '5678CD',
                    'city' => 'Rotterdam',
                    'country' => 'NL',
                ],
                'phone' => [
                    'mobile' => '0698765432',
                ],
                'email' => 'jane.smith@example.com',
            ],
            'articles' => [
                [
                    'identifier' => 'ART002',
                    'description' => 'Expensive Item',
                    'quantity' => 1,
                    'price' => 500.00,
                ],
            ],
        ]);

        $this->assertTrue($response->isPendingProcessing());
        $this->assertTrue($response->hasRedirect());
        $this->assertEquals($redirectUrl, $response->getRedirectUrl());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-IN3OLD-INSTALL-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(500.00, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_an_in3old_refund(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $originalTxKey = 'ORIG_IN3OLD_TX_KEY';

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
                        'Name' => 'Capayable',
                        'Action' => 'Refund',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-IN3OLD-REFUND-001',
                'Currency' => 'EUR',
                'AmountCredit' => 50.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('in3old')->refund([
            'amountCredit' => 50.00,
            'invoice' => 'INV-IN3OLD-REFUND-001',
            'originalTransactionKey' => $originalTxKey,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-IN3OLD-REFUND-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(50.00, $response->getAmountCredit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_payment_with_company_data(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $redirectUrl = 'https://capayable.nl/redirect/' . $transactionKey;

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 791, 'Description' => 'Pending processing'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Redirecting to Capayable'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => [
                    'Name' => 'Redirect',
                    'RedirectURL' => $redirectUrl,
                ],
                'Services' => [
                    [
                        'Name' => 'Capayable',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-IN3OLD-COMPANY-001',
                'Currency' => 'EUR',
                'AmountDebit' => 1000.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('in3old')->pay([
            'amountDebit' => 1000.00,
            'invoice' => 'INV-IN3OLD-COMPANY-001',
            'billing' => [
                'recipient' => [
                    'firstName' => 'Business',
                    'lastName' => 'Owner',
                ],
                'address' => [
                    'street' => 'Business Street',
                    'houseNumber' => '100',
                    'zipcode' => '1000AA',
                    'city' => 'Utrecht',
                    'country' => 'NL',
                ],
                'phone' => [
                    'mobile' => '0612345678',
                ],
                'email' => 'business@example.com',
            ],
            'company' => [
                'name' => 'Test Company B.V.',
                'chamberOfCommerce' => '12345678',
            ],
            'articles' => [
                [
                    'identifier' => 'ART003',
                    'description' => 'Business Item',
                    'quantity' => 2,
                    'price' => 500.00,
                ],
            ],
        ]);

        $this->assertTrue($response->isPendingProcessing());
        $this->assertTrue($response->hasRedirect());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-IN3OLD-COMPANY-001', $response->getInvoice());
        $this->assertEquals(1000.00, $response->getAmountDebit());
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
                        'Name' => 'Capayable',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-IN3OLD-STATUS',
                'Currency' => 'EUR',
                'AmountDebit' => 10.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('in3old')->pay([
            'amountDebit' => 10.00,
            'invoice' => 'INV-IN3OLD-STATUS',
            'billing' => [
                'recipient' => [
                    'firstName' => 'Test',
                    'lastName' => 'User',
                ],
                'address' => [
                    'street' => 'Test Street',
                    'houseNumber' => '1',
                    'zipcode' => '1234AB',
                    'city' => 'Amsterdam',
                    'country' => 'NL',
                ],
                'phone' => [
                    'mobile' => '0612345678',
                ],
                'email' => 'test@example.com',
            ],
            'articles' => [],
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
            'cancelled' => [890, 'isCanceled'],
        ];
    }
}
