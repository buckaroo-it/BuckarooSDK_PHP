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

class PaypalTest extends BuckarooTestCase
{
    /**
     * @test
     */
    public function it_creates_a_paypal_payment()
    {
        $response = $this->buckaroo->method('paypal')->pay($this->getBasePayPayload());

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_paypal_recurrent_payment()
    {
        $response = $this->buckaroo->method('paypal')->payRecurrent($this->getBasePayPayload([],[
            'originalTransactionKey' => '8E84AF7D9BDF45368D60AC4ED7EA1733',
        ]));

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @test
     */
    public function it_creates_a_paypal_extra_info()
    {
        $response = $this->buckaroo->method('paypal')->extraInfo($this->getBasePayPayload([],[
            'customer' => [
                'name' => 'John Smith',
            ],
            'address' => [
                'street' => 'Hoofstraat 90',
                'street2' => 'Street 2',
                'city' => 'Heerenveen',
                'state' => 'Friesland',
                'zipcode' => '8441AB',
                'country' => 'NL',
            ],
            'phone' => [
                'mobile' => '0612345678',
            ],
        ]));

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_paypal_refund()
    {
        $response = $this->buckaroo->method('paypal')->refund($this->getRefundPayload([
            'originalTransactionKey' => 'CE26373CFB64485CB7DFB1BD656066C1',
        ]));

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @test
     */
    public function it_creates_a_paypal_pay_remainder()
    {
        $giftCardResponse = $this->buckaroo->method('giftcard')->pay($this->getBasePayPayload([], [
            'amountDebit' => 10,
            'name' => 'boekenbon',
            'intersolveCardnumber' => '0000000000000000001',
            'intersolvePIN' => '500',
        ]));

        $this->assertTrue($giftCardResponse->isSuccess());

        $response = $this->buckaroo->method('paypal')->payRemainder($this->getBasePayPayload([], [
            'originalTransactionKey' => $giftCardResponse->data('RelatedTransactions')[0]['RelatedTransactionKey'],
            'amountDebit' => 9.50,
        ]));

        $this->assertTrue($response->isPendingProcessing());
    }
}
