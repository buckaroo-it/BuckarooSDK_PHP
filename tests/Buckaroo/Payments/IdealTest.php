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
    /**
     * @return void
     * @test
     */
    public function it_get_ideal_issuers()
    {
        $response = $this->buckaroo->method('ideal')->issuers();

        $this->assertIsArray($response);
        foreach ($response as $item) {
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
        $response = $this->buckaroo->method('ideal')->pay($this->getBasePayPayload([], [
            'pushURL' => 'https://example.com/buckaroo/push',
            'issuer' => 'ABNANL2A',
            'customParameters' => [
                'CustomerBillingFirstName' => 'Test'
            ],
        ]));

        $this->assertTrue($response->isWaitingOnUserInput());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_ideal_fast_checkout_payment()
    {
        $response = $this->buckaroo->method('ideal')->payFastCheckout($this->getBasePayPayload([], [
            'pushURL' => 'https://buckaroo.dev/push',
            'customParameters' => [
                'CustomerBillingFirstName' => 'Test'
            ],
        ]));

        $this->assertTrue($response->isWaitingOnUserInput());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_ideal_pay_remainder()
    {
        $giftCardResponse = $this->buckaroo->method('giftcard')->pay($this->getBasePayPayload([], [
            'amountDebit' => 10,
            'name' => 'boekenbon',
            'intersolveCardnumber' => '0000000000000000001',
            'intersolvePIN' => '500',
        ]));

        $this->assertTrue($giftCardResponse->isSuccess());

        $response = $this->buckaroo->method('ideal')->payRemainder($this->getBasePayPayload([], [
            'originalTransactionKey' => $giftCardResponse->data('RelatedTransactions')[0]['RelatedTransactionKey'],
            'amountDebit' => 5.00,
            'pushURL' => 'https://buckaroo.dev/push',
            'issuer' => 'ABNANL2A',
            'customParameters' => [
                'CustomerBillingFirstName' => 'Test'
            ],
        ]));

        $this->assertTrue($response->isWaitingOnUserInput());
    }

    /**
     * @test
     */
    public function it_creates_a_ideal_refund()
    {
        $response = $this->buckaroo->method('ideal')->refund($this->getRefundPayload([
            'originalTransactionKey' => '4EADF2E4BDFA41AD85BDDAB026529D65',
            'pushURL' => 'https://buckaroo.dev/push',

        ]));

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @test
     */
    public function it_creates_a_ideal_instant_refund()
    {
        $response = $this->buckaroo->method('ideal')->instantRefund($this->getRefundPayload([
            'originalTransactionKey' => '4EADF2E4BDFA41AD85BDDAB026529D65',
            'pushURL' => 'https://buckaroo.dev/push',

        ]));

        $this->assertTrue($response->isPendingProcessing());
    }
}
