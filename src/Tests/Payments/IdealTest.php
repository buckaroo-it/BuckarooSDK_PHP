<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class IdealTest extends BuckarooTestCase
{
    protected function setUp(): void
    {
        $this->paymentPayload = ([
            'issuer' => 'ABNANL2A',
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
    public function it_creates_a_ideal_payment()
    {
        $response = $this->buckaroo->payment('idealprocessing')->pay($this->paymentPayload);
        $this->assertTrue($response->isPendingProcessing());

        $response = $this->buckaroo->payment('ideal')->pay(json_encode($this->paymentPayload));
        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_ideal_refund()
    {
        $response = $this->buckaroo->payment('ideal')->refund($this->refundPayload);
        $this->assertTrue($response->isSuccess());
    }
}