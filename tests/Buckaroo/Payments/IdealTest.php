<?php
/*
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * It is available through the world-wide-web at this URL:
 * https://tldrlegal.com/license/mit-license
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to support@buckaroo.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact support@buckaroo.nl for more information.
 *
 * @copyright Copyright (c) Buckaroo B.V.
 * @license   https://tldrlegal.com/license/mit-license
 */

namespace Tests\Buckaroo\Payments;

use Tests\Buckaroo\BuckarooTestCase;

class IdealTest extends BuckarooTestCase
{
    protected array $paymentPayload;
    protected array $refundPayload;

    protected function setUp(): void
    {
        $this->paymentPayload = [
            'invoice' => uniqid(),
            'amountDebit' => 10.10,
            'issuer' => 'ABNANL2A',
            'pushURL' => 'https://buckaroo.dev/push',
            'returnURL' => 'https://buckaroo.dev/return',
            'clientIP' => [
                'address' => '123.456.789.123',
                'type' => 0,
            ],
            'customParameters' => [
                'CustomerBillingFirstName' => 'test'
            ],
            'additionalParameters' => [
                'initiated_by_magento' => 1,
                'service_action' => 'something',
            ],
        ];

        $this->refundPayload = [
            'invoice' => 'testinvoice 123', //Set invoice number of the transaction to refund
            'originalTransactionKey' => '4E8BD922192746C3918BF4077CXXXXXX',
            //Set transaction key of the transaction to refund
            'amountCredit' => 1.23,
            'clientIP' => [
                'address' => '123.456.789.123',
                'type' => 0,
            ],
            'additionalParameters' => [
                'initiated_by_magento' => '1',
                'service_action' => 'something',
            ],
        ];
    }

    /**
     * @return void
     * @test
     */
    public function it_get_ideal_issuers()
    {
        $response = $this->buckaroo->method('ideal')->issuers();

        $this->assertIsArray($response);
        foreach ($response as $item)
        {
            $this->assertIsArray($item);
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('name', $item);
        }
    }
    
    /**
     * @return void
     * @test
     */
    public function it_creates_a_ideal_payment()
    {
        $response = $this->buckaroo->method('ideal')->pay($this->paymentPayload);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_ideal_fast_checkout_payment()
    {
        $response = $this->buckaroo->method('ideal')->payFastCheckout([
            'amountDebit'    => 10.10,
            'shippingCost'   => 0.10,
            'invoice'   => uniqid(),
        ]);

        $this->assertTrue($response->isWaitingOnUserInput());
    }

    /**
     * @test
     */
    public function it_creates_a_ideal_refund()
    {
        $response = $this->buckaroo->method('ideal')->refund($this->refundPayload);

        $this->assertTrue($response->isFailed());
    }
}
