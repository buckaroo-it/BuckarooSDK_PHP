<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class TransferTest extends BuckarooTestCase
{
    protected function setUp(): void
    {
        $this->paymentPayload = ([
            'customerGender' => 0, // 0 = unkinown / 1 = male / 2 = female
            'customerFirstName' => 'John',
            'customerLastName' => 'Smith',
            'customerEmail' => 'your@email.com',
            'customerCountry' => 'NL',
            'dueData' => date(),
            'sendMail' => true,
            'amountDebit' => 10.10
        ]);
        
        $this->refundPayload = [
            'invoice'   => '', //Set invoice number of the transaction to refund
            'originalTransactionKey' => '', //Set transaction key of the transaction to refund
            'amountCredit' => 10.10
        ];
        
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_transfer_payment()
    {
        $response = $this->buckaroo->payment('transfer')->pay($this->paymentPayload);
        $this->assertTrue($response->isPendingProcessing());

    }

}