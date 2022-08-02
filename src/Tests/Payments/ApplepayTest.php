<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class ApplepayTest extends BuckarooTestCase
{
//    /**
//     * @test
//     */
//    public function it_creates_a_applepay_payment()
//    {
//        $response = $this->buckaroo->payment('applepay')->pay([
//            'amountDebit' => 10,
//            'invoice' => uniqid(),
//            'paymentData' => 'XXXXXXXXXXXXX',
//            'customerCardName'  => 'XXXXXXXXXXXXX'
//        ]);
//
//        $this->assertTrue($response->isPendingProcessing());
//    }

    /**
     * @test
     */
    public function it_creates_a_applepay_refund()
    {
        $response = $this->buckaroo->method('applepay')->refund([
            'amountCredit' => 10,
            'invoice' => '10000480',
            'originalTransactionKey' => '9AA4C81A08A84FA7B68E6A6A6291XXXX'
        ]);

        $this->assertTrue($response->isFailed());
    }
}