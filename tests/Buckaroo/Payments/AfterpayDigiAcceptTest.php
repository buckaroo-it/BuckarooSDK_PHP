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

class AfterpayDigiAcceptTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_afterpaydigiaccept_payment()
    {
        $response = $this->buckaroo->method('afterpaydigiaccept')->pay(array_merge(
            $this->getBasePayPayload(),
            [
                'b2b' => true,
                'addressesDiffer' => true,
                'customerIPAddress' => '0.0.0.0',
                'amountDebit' => 100.80,
                'shippingCosts' => 0.5,
                'costCentre' => 'Test',
                'department' => 'Test',
                'establishmentNumber' => '123456',
                'billing' => $this->getBillingPayload(['category', 'careOf']),
                'shipping' => $this->getShippingPayload(['category', 'careOf']),
                'articles' => $this->getArticlesPayload(['type', 'vatPercentage']),
            ]
        ));

        self::$payTransactionKey = $response->getTransactionKey();

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_afterpaydigiaccept_authorize()
    {
        $response = $this->buckaroo->method('afterpaydigiaccept')->authorize(array_merge(
            $this->getBasePayPayload(),
            [
                'b2b' => true,
                'addressesDiffer' => true,
                'customerIPAddress' => '0.0.0.0',
                'amountDebit' => 100.80,
                'shippingCosts' => 0.5,
                'costCentre' => 'Test',
                'department' => 'Test',
                'establishmentNumber' => '123456',
                'billing' => $this->getBillingPayload(['category', 'careOf']),
                'shipping' => $this->getShippingPayload(['category', 'careOf']),
                'articles' => $this->getArticlesPayload(['type', 'vatPercentage']),
            ]
        ));

        self::$authorizeTransactionKey = $response->getTransactionKey();

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     * @depends it_creates_a_afterpaydigiaccept_authorize
     */
    public function it_creates_a_afterpaydigiaccept_cancelAuthorize()
    {
        if (empty(self::$authorizeTransactionKey)) {
            $this->markTestSkipped('Skipping cancelAuthorize: No authorization transaction key is set.');
        }

        $response = $this->buckaroo->method('afterpaydigiaccept')->cancelAuthorize($this->getRefundPayload([
            'shippingCosts' => 0.5,
            'amountCredit' => 0.50,
            'originalTransactionKey' => self::$authorizeTransactionKey,
        ]));

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_afterpaydigiaccept_capture()
    {
        $response = $this->buckaroo->method('afterpaydigiaccept')->authorize(array_merge(
            $this->getBasePayPayload(),
            [
                'b2b' => true,
                'addressesDiffer' => true,
                'customerIPAddress' => '0.0.0.0',
                'amountDebit' => 100.80,
                'shippingCosts' => 0.5,
                'costCentre' => 'Test',
                'department' => 'Test',
                'establishmentNumber' => '123456',
                'billing' => $this->getBillingPayload(['category', 'careOf']),
                'shipping' => $this->getShippingPayload(['category', 'careOf']),
                'articles' => $this->getArticlesPayload(['type', 'vatPercentage']),
            ]
        ));

        self::$authorizeTransactionKey = $response->getTransactionKey();
        $this->assertTrue($response->isSuccess());

        $response = $this->buckaroo->method('afterpaydigiaccept')->capture($this->getPayPayload([
            'originalTransactionKey' => self::$authorizeTransactionKey,
            'amountDebit' => 100.80,
            'billing' => $this->getBillingPayload(['category', 'careOf']),
            'shipping' => $this->getShippingPayload(['category', 'careOf']),
        ]));

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @test
     * @depends it_creates_a_afterpaydigiaccept_payment
     */
    public function it_creates_a_afterpaydigiaccept_refund()
    {
        if (empty(self::$payTransactionKey)) {
            $this->markTestSkipped('Skipping refund: No original transaction key is set.');
        }

        $response = $this->buckaroo->method('afterpaydigiaccept')->refund(
            $this->getRefundPayload([
                'amountCredit' => 100.80,
                'originalTransactionKey' => self::$payTransactionKey,
            ])
        );

        $this->assertTrue($response->isSuccess());
    }
}
