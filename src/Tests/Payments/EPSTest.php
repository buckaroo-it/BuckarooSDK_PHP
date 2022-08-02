<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class EPSTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_eps_payment()
    {
        $response = $this->buckaroo->method('eps')->pay([
            'invoice' => uniqid(),
            'amountDebit' => 10.10
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @test
     */
    public function it_creates_a_eps_refund()
    {
        $response = $this->buckaroo->method('eps')->refund([
            'amountCredit'              => 10,
            'invoice'                   => 'testinvoice 123',
            'description'               => 'refund',
            'originalTransactionKey'    => '2D04704995B74D679AACC59F87XXXXXX'
        ]);

        $this->assertTrue($response->isFailed());
    }
}