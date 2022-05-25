<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class PayconiqTest extends BuckarooTestCase
{
    /**
     * @test
     */
    public function it_creates_a_payconiq_payment()
    {
        $response = $this->buckaroo->payment('payconiq')->pay([
            'amountDebit' => 10,
            'invoice' => uniqid()
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }
}