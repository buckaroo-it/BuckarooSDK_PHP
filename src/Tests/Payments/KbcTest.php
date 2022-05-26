<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class KbcTest extends BuckarooTestCase
{
    protected function setUp(): void
    {
        $this->paymentPayload = ([
            'amountDebit' => 10.10
        ]);
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_kbc_payment()
    {
        $response = $this->buckaroo->payment('kbcpaymentbutton')->pay($this->paymentPayload);
        $this->assertTrue($response->isPendingProcessing());
    }

}