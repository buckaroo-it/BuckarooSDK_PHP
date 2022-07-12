<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Config\Config;
use Buckaroo\Tests\BuckarooTestCase;

class CustomConfig extends Config
{
    public function __construct()
    {
        $websiteKey = 'Set Key';
        $secretKey = 'From other resources like DB/ENV/Platform Config';

        parent::__construct($websiteKey, $secretKey);
    }
}

class IdealTest extends BuckarooTestCase
{
    protected function setUp(): void
    {
        $this->paymentPayload = ([
            'invoice' => uniqid(),
            'amountDebit' => 10.10,
            'issuer' => 'ABNANL2A'
        ]);

        $this->refundPayload = [
            'invoice'   => 'testinvoice 123', //Set invoice number of the transaction to refund
            'originalTransactionKey' => '4E8BD922192746C3918BF4077CXXXXXX', //Set transaction key of the transaction to refund
            'amountCredit' => 1.23
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

//        $customConfig = new CustomConfig();
//        $customConfig->currency('AUD');
//
//        $response = $this->buckaroo->setConfig($customConfig)->payment('ideal')->pay(json_encode($this->paymentPayload));
//        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_ideal_refund()
    {
        $response = $this->buckaroo->payment('ideal')->refund($this->refundPayload);

        $this->assertTrue($response->isFailed());
    }
}