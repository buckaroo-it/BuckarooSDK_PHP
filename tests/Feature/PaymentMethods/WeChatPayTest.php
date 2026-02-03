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
class WeChatPayTest extends FeatureTestCase
{
    /** @test */
    public function it_creates_a_wechatpay_payment_with_redirect(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $redirectUrl = 'https://qr.wechat.com/pay?token=' . $transactionKey;

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 791, 'Description' => 'Pending processing'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Redirecting to WeChat Pay'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => [
                    'Name' => 'Redirect',
                    'RedirectURL' => $redirectUrl,
                ],
                'Services' => [
                    [
                        'Name' => 'wechatpay',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-WECHAT-001',
                'Currency' => 'EUR',
                'AmountDebit' => 88.88,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('wechatpay')->pay([
            'amountDebit' => 88.88,
            'invoice' => 'INV-WECHAT-001',
        ]);

        $this->assertTrue($response->isPendingProcessing());
        $this->assertTrue($response->hasRedirect());
        $this->assertEquals($redirectUrl, $response->getRedirectUrl());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals('INV-WECHAT-001', $response->getInvoice());
        $this->assertEquals('EUR', $response->getCurrency());
        $this->assertEquals(88.88, $response->getAmountDebit());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_a_wechatpay_payment_with_locale(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $redirectUrl = 'https://qr.wechat.com/pay?token=' . $transactionKey;

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/Transaction*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 791, 'Description' => 'Pending processing'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Redirecting to WeChat Pay'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'RequiredAction' => [
                    'Name' => 'Redirect',
                    'RedirectURL' => $redirectUrl,
                ],
                'Services' => [
                    [
                        'Name' => 'wechatpay',
                        'Action' => 'Pay',
                        'Parameters' => [
                            ['Name' => 'Locale', 'Value' => 'zh_CN'],
                        ],
                    ],
                ],
                'Invoice' => 'INV-WECHAT-LOCALE-001',
                'Currency' => 'CNY',
                'AmountDebit' => 100.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('wechatpay')->pay([
            'amountDebit' => 100.00,
            'invoice' => 'INV-WECHAT-LOCALE-001',
            'locale' => 'zh_CN',
        ]);

        $this->assertTrue($response->isPendingProcessing());
        $this->assertTrue($response->hasRedirect());
        $this->assertEquals($redirectUrl, $response->getRedirectUrl());
        $this->assertEquals('INV-WECHAT-LOCALE-001', $response->getInvoice());
        $this->assertEquals('CNY', $response->getCurrency());
        $this->assertEquals(100.00, $response->getAmountDebit());
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
                        'Name' => 'wechatpay',
                        'Action' => 'Pay',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => 'INV-STATUS-001',
                'Currency' => 'EUR',
                'AmountDebit' => 10.00,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('wechatpay')->pay([
            'amountDebit' => 10.00,
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
