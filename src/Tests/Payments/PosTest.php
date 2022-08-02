<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class PosTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_pos_payment()
    {
        $response = $this->buckaroo->method('pospayment')->pay([
            'invoice' => uniqid(),
            'amountDebit' => 10.10,
            'terminalID' => '50000001',
        ]);

        $this->assertTrue($response->isSuccess());

    }
}