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

class AlipayTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_alipay_payment()
    {
        $response = $this->buckaroo->method('alipay')->pay($this->getBasePayPayload([], [
            'useMobileView' => true,
        ]));

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_alipay_pay_remainder()
    {
        $giftCardResponse = $this->buckaroo->method('giftcard')->pay($this->getBasePayPayload([], [
            'amountDebit' => 10,
            'name' => 'boekenbon',
            'intersolveCardnumber' => '0000000000000000001',
            'intersolvePIN' => '500',
        ]));

        $this->assertTrue($giftCardResponse->isSuccess());

        $response = $this->buckaroo->method('alipay')->payRemainder($this->getBasePayPayload([], [
            'originalTransactionKey' => $giftCardResponse->data('RelatedTransactions')[0]['RelatedTransactionKey'],
            'amountDebit' => 9.50,
            'useMobileView' => true,
        ]));

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_alipay_refund()
    {
        $response = $this->buckaroo->method('alipay')->refund($this->getRefundPayload([
            'originalTransactionKey' => 'CF16AD6BAB2F4B0C8C435654AE5E5740',
        ]));

        $this->assertTrue($response->isSuccess());
    }
}
