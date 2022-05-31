<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class TrustlyTest extends BuckarooTestCase
{
    /**
     * @test
     */
    public function it_creates_a_trustly_payment()
    {
        $response = $this->buckaroo->payment('trustly')->pay([
            'amountDebit' => 10,
            'invoice' => uniqid(),
            'serviceParameters' => [
                'customer'      => [
                    'firstName' => 'Test',
                    'lastName' => 'Aflever',
                    'country' => 'DE'
                ]
            ]
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_trustly_refund()
    {
        $response = $this->buckaroo->payment('trustly')->refund([
            'amountCredit' => 10,
            'invoice'       => 'testinvoice 123',
            'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX'
        ]);

        $this->assertTrue($response->isFailed());
    }
}