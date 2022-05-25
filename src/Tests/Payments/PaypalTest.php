<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class PaypalTest extends BuckarooTestCase
{
    /**
     * @test
     */
    public function it_creates_a_paypal_payment()
    {
        $response = $this->buckaroo->payment('paypal')->pay([
            'amountDebit' => 10,
            'invoice' => uniqid()
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_paypal_refund()
    {
        $response = $this->buckaroo->payment('paypal')->refund([
            'amountCredit' => 10,
            'invoice'       => 'testinvoice 123',
            'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX'
        ]);

        $this->assertTrue($response->isFailed());
    }

}