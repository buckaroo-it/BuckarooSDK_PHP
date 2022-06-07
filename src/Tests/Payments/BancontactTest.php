<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class BancontactTest extends BuckarooTestCase
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
    public function it_creates_a_bancontact_payment()
    {
        $response = $this->buckaroo->payment('bancontactmrcash')->pay($this->paymentPayload);

        $this->assertTrue($response->isWaitingOnUserInput());
    }

}