<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class RequestToPayTest extends BuckarooTestCase
{
//    /**
//     * @test
//     */
//    public function it_creates_request_to_pay_payment()
//    {
//        $response = $this->buckaroo->method("requesttopay")->pay([
//            'amountDebit'       => 3.5,
//            'invoice'           => uniqid(),
//            'customer'      => [
//                'name'          => 'J. De Tester'
//            ]
//        ]);
//
//        $this->assertTrue($response->isPendingProcessing());
//    }

    /**
     * @test
     */
    public function it_creates_a_request_to_pay_refund()
    {
        $response = $this->buckaroo->method('requesttopay')->refund([
            'amountCredit' => 10,
            'invoice'       => 'testinvoice 123',
            'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX'
        ]);

        $this->assertTrue($response->isFailed());
    }
}