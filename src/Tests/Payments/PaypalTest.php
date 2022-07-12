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
    public function it_creates_a_paypal_recurrent_payment()
    {
        $response = $this->buckaroo->payment('paypal')->payRecurrent([
            'amountDebit' => 10,
            'originalTransactionKey' => 'C32C0B52E1FE4A37835FFB1716XXXXXX',
            'invoice' => uniqid()
        ]);

        $this->assertTrue($response->isFailed());
    }

    /**
     * @test
     */
    public function it_creates_a_paypal_extra_info()
    {
        $response = $this->buckaroo->payment('paypal')->extraInfo([
            'amountDebit' => 10,
            'invoice' => uniqid(),
            'customer'  => [
                'name'      => 'John Smith'
            ],
            'address'   => [
                'street'       => 'Hoofstraat 90',
                'street2'       => 'Street 2',
                'city'          => 'Heerenveen',
                'state'         => 'Friesland',
                'zipcode'       => '8441AB',
                'country'       => 'NL'
            ],
            'phone'             => [
                'mobile'        => '0612345678'
            ]
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