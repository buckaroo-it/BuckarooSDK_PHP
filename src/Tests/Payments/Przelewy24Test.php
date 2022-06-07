<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class Przelewy24Test extends BuckarooTestCase
{
    /**
     * @test
     */
    public function it_creates_przelewy24_payment()
    {
        $response = $this->buckaroo->payment("przelewy24")->pay([
            'amountDebit'       => 3.5,
            'invoice'           => uniqid(),
            'serviceParameters' => [
                'customer'      => [
                    'firstName'     => 'John',
                    'lastName'      => 'Smith',
                    'email'         => 'test@test.nl'
                ]
            ]
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_przelewy24_refund()
    {
        $response = $this->buckaroo->payment('przelewy24')->refund([
            'amountCredit' => 10,
            'invoice'       => 'testinvoice 123',
            'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX'
        ]);

        $this->assertTrue($response->isFailed());
    }
}