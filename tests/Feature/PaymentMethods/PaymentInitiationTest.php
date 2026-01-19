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
class PaymentInitiationTest extends TestCase
{
    /** @test */
    public function it_creates_a_payment_initiation_with_redirect(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $redirectUrl = 'https://bank.example.com/auth?trx=' . $transactionKey;

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 791, 'Description' => 'Pending processing'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Redirecting to bank'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => [
                    'Name' => 'Redirect',
                    'RedirectURL' => $redirectUrl,
                ],
                'Services' => [
                    [
                        'Name' => 'PayByBank',
                        'Action' => 'Pay',
                        'Parameters' => [
                            ['Name' => 'issuer', 'Value' => 'BANK123'],
                            ['Name' => 'countryCode', 'Value' => 'NL'],
                        ],
                    ]
                ],
                'Invoice' => 'INV-PBI-001',
                'Currency' => 'EUR',
                'AmountDebit' => 50.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('paybybank')->pay([
            'amountDebit' => 50.00,
            'invoice' => 'INV-PBI-001',
            'issuer' => 'BANK123',
            'countryCode' => 'NL',
        ]);

        $this->assertTrue($response->isPendingProcessing());
        $this->assertTrue($response->hasRedirect());
        $this->assertEquals($redirectUrl, $response->getRedirectUrl());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-PBI-001', $response->getInvoice());
    }

    /** @test */
    public function it_fetches_issuers(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('GET', '*/json/Transaction/Specification/PayByBank*', [
                'Services' => [
                    [
                        'Name' => 'PayByBank',
                        'Parameters' => [
                            ['Name' => 'issuer', 'ListItemDescription' => 'Test Bank NL', 'ListItemID' => 'TESTNL01'],
                            ['Name' => 'issuer', 'ListItemDescription' => 'Test Bank DE', 'ListItemID' => 'TESTDE01'],
                        ],
                    ]
                ],
            ]),
        ]);

        $issuers = $this->buckaroo->method('paybybank')->issuers();

        $this->assertIsArray($issuers);
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
                        'Name' => 'PayByBank',
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

        $response = $this->buckaroo->method('paybybank')->pay([
            'amountDebit' => 10.00,
            'invoice' => 'INV-STATUS-001',
            'issuer' => 'BANK123',
            'countryCode' => 'NL',
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

    /**
     * @test
     * @dataProvider countryCodeProvider
     */
    public function it_works_with_different_country_codes(string $countryCode): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $redirectUrl = "https://bank.example.com/auth?trx={$transactionKey}";

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 791, 'Description' => 'Pending processing'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Redirecting to bank'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => [
                    'Name' => 'Redirect',
                    'RedirectURL' => $redirectUrl,
                ],
                'Services' => [
                    [
                        'Name' => 'PayByBank',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ]
                ],
                'Invoice' => 'INV-COUNTRY-001',
                'Currency' => 'EUR',
                'AmountDebit' => 25.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('paybybank')->pay([
            'amountDebit' => 25.00,
            'invoice' => 'INV-COUNTRY-001',
            'issuer' => 'BANK123',
            'countryCode' => $countryCode,
        ]);

        $this->assertTrue($response->isPendingProcessing());
        $this->assertSame($transactionKey, $response->getTransactionKey());
        $this->assertSame('INV-COUNTRY-001', $response->getInvoice());
    }

    public static function countryCodeProvider(): array
    {
        return [
            ['NL'],
            ['DE'],
            ['BE'],
        ];
    }
}
