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
        $response = $this->buckaroo->payment('pospayment')->pay([
            'amountDebit' => 10.10,
            'serviceParameters' => [
                'terminalId' => '50000001',
            ]
        ]);
        $this->assertTrue($response->isPendingProcessing());

    }
}