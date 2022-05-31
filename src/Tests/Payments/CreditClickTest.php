<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class CreditClickTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditclick_payment()
    {
        $response = $this->buckaroo->payment('creditclick')->pay([
            'amountDebit' => 10,
            'invoice' => uniqid(),
            'serviceParameters' => [
                'customer'      => [
                    'firstName' => 'Test',
                    'lastName' => 'Aflever',
                    'email' => 'billingcustomer@buckaroo.nl'
                ]
            ]
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_creditclick_refund()
    {
        $response = $this->buckaroo->payment('creditclick')->refund([
            'amountCredit' => 10,
            'invoice'       => 'testinvoice 123',
            'description'   => 'refund',
            'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX',
            'serviceParameters' => [
                'reason'      => 'RequestedByCustomer'
            ]
        ]);

        $this->assertTrue($response->isFailed());
    }
}