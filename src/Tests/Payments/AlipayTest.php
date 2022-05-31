<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class AlipayTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_alipay_payment()
    {
        $response = $this->buckaroo->payment('alipay')->pay([
            'amountDebit' => 10,
            'invoice' => uniqid(),
            'serviceParameters' => [
                'useMobileView' => true
            ]
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_alipay_refund()
    {
        $response = $this->buckaroo->payment('alipay')->refund([
            'amountCredit' => 10,
            'invoice'       => 'testinvoice 123',
            'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX',
        ]);

        $this->assertTrue($response->isFailed());
    }
}