<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class EpsTest extends BuckarooTestCase
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
    public function it_creates_a_eps_payment()
    {
        $response = $this->buckaroo->payment('eps')->pay($this->paymentPayload);
        $this->assertTrue($response->isSuccess());
    }

}