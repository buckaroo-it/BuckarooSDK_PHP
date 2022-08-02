<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class WeChatPayTest extends BuckarooTestCase
{
    /**
     * @test
     */
    public function it_creates_a_wechat_payment()
    {
        $response = $this->buckaroo->method('wechatpay')->pay([
            'amountDebit'   => 10,
            'invoice'       => uniqid(),
            'locale'        => 'en-US'
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_wechat_refund()
    {
        $response = $this->buckaroo->method('wechatpay')->refund([
            'amountCredit' => 10,
            'invoice'       => 'testinvoice 123',
            'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX',
        ]);

        $this->assertTrue($response->isFailed());
    }
}