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

class WeChatPayTest extends BuckarooTestCase
{
    /**
     * @test
     */
    public function it_creates_a_wechat_payment()
    {
        $response = $this->buckaroo->method('wechatpay')->pay($this->getBasePayPayload([
            'locale' => 'en-US',
        ]));

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_wechat_refund()
    {
        $response = $this->buckaroo->method('wechatpay')->refund($this->getRefundPayload([
            'originalTransactionKey' => '67E70539519D427595D86AC50DA4F7CE',
        ]));

        $this->assertTrue($response->isSuccess());
    }
}
