<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class BelfiusTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_belfius_payment()
    {
        $response = $this->buckaroo->payment('belfius')->pay([
            'amountDebit' => 10.10
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_belfius_refund()
    {
        $response = $this->buckaroo->payment('belfius')->refund([
            'amountCredit' => 10,
            'invoice' => '10000480',
            'originalTransactionKey' => '0EF39AA94BD64FF38F1540DEB6XXXXXX'
        ]);

        $this->assertTrue($response->isFailed());
    }
}