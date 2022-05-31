<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class BelfiusTest extends BuckarooTestCase
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
    public function it_creates_a_belfius_payment()
    {
        $response = $this->buckaroo->payment('belfius')->pay($this->paymentPayload);
        $this->assertTrue($response->isPendingProcessing());
    }

}