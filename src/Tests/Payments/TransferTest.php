<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;
use Buckaroo\Resources\Constants\Gender;

class TransferTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_transfer_payment()
    {
        $response = $this->buckaroo->payment('transfer')->pay([
            'amountDebit' => 10.10,
            'email' => 'your@email.com',
            'country' => 'NL',
            'dateDue' => date("Y-m-d"),
            'sendMail' => true,
            'customer' => [
                'gender' => Gender::MALE,
                'firstName' => 'John',
                'lastName' => 'Smith'
            ]
        ]);

        $this->assertTrue($response->isAwaitingConsumer());
    }

    /**
     * @test
     */
    public function it_creates_a_transfer_refund()
    {
        $response = $this->buckaroo->payment('transfer')->refund([
            'amountCredit' => 10,
            'invoice'       => 'testinvoice 123',
            'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX'
        ]);

        $this->assertTrue($response->isFailed());
    }

}