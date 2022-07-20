<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class GiropayTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_giropay_payment()
    {
        $response = $this->buckaroo->payment('giropay')->pay([
            'invoice' => uniqid(),
            'bic'           => 'GENODETT488',
            'amountDebit'   => 10.10
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_giropay_refund()
    {
        $response = $this->buckaroo->payment('giropay')->refund([
            'amountCredit'              => 10,
            'invoice'                   => 'testinvoice 123',
            'description'               => 'refund',
            'originalTransactionKey'    => '2D04704995B74D679AACC59F87XXXXXX'
        ]);

        $this->assertTrue($response->isFailed());
    }
}