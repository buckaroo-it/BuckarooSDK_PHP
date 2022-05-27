<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class GiropayTest extends BuckarooTestCase
{
    protected function setUp(): void
    {
        $this->paymentPayload = ([
            'bic' => 'GENODETT488',
            'amountDebit' => 10.10
        ]);
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_giropay_payment()
    {
        $response = $this->buckaroo->payment('giropay')->pay($this->paymentPayload);
        $this->assertTrue($response->isPendingProcessing());
    }

}