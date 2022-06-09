<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class SepaTest extends BuckarooTestCase
{
    protected function setUp(): void
    {
        $this->paymentPayload = ([
            'amountDebit' => 10.10,
            'serviceParameters' => [
                'iban'              => 'NL13TEST0123456789',
                'bic'               => 'TESTNL2A',
                'collectDate'       => '2022-08-01',
                'mandateReference'  => '1DCtestreference',
                'mandateDate'       => '2022-07-03',
                'customer'      => [
                    'name'          => 'John Smith'
                ]
            ]
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