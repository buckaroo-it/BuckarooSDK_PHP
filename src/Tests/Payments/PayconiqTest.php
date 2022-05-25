<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class PayconiqTest extends BuckarooTestCase
{
    /**
     * @test
     */
    public function it_creates_a_payconiq_payment()
    {
        $response = $this->buckaroo->payment('payconiq')->pay([
            'amountDebit' => 10,
            'invoice' => uniqid()
        ]);

        //$this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_payconiq_refund()
    {
        $response = $this->buckaroo->payment('payconiq')->refund([
            'amountCredit' => 10,
            'invoice'       => 'testinvoice 123',
            'originalTransactionKey' => '4E8BD922192746C3918BF4077CXXXXXX'
        ]);

        $this->assertTrue($response->isFailed());
    }
}