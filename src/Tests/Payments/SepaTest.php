<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class SepaTest extends BuckarooTestCase
{
    protected function setUp(): void
    {
        $this->paymentPayload = ([
            'customerAccountName' => 'TEST BANK USED BY IBAN SERVICE',
            'customerBic' => 'TESTNL2A',
            'customerIban' => 'NL13TEST0123456789',
            'amountDebit' => 10.10
        ]);
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_sepa_payment()
    {
        $response = $this->buckaroo->payment('sepadirectdebit')->pay($this->paymentPayload);
        $this->assertTrue($response->isPendingProcessing());
    }

}