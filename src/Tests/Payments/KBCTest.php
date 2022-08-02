<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class KBCTest extends BuckarooTestCase
{
    protected function setUp(): void
    {
        $this->paymentPayload = ([
            'invoice' => uniqid(),
            'amountDebit' => 10.10
        ]);
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_kbc_payment()
    {
        $response = $this->buckaroo->method('kbcpaymentbutton')->pay($this->paymentPayload);
        $this->assertTrue($response->isPendingProcessing());
    }

}