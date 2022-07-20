<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class KlarnaKPTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_klarnakp_payment()
    {
        $response = $this->buckaroo->payment('klarnakp')->pay([
            'amountDebit'       => 50.30,
            'order'             => uniqid(),
            'invoice'           => uniqid(),
            'reservationNumber' => '2377577452',
            'serviceParameters' => [
                'articles'      => [
                    [
                        'identifier' => uniqid(),
                        'quantity' => '2'
                    ],
                    [
                        'identifier' => uniqid(),
                        'quantity' => '2'
                    ],
                ]
            ]
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_klarnakp_refund()
    {
        $response = $this->buckaroo->payment('klarnakp')->refund([
            'amountCredit' => 10,
            'invoice' => '10000480',
            'originalTransactionKey' => '9AA4C81A08A84FA7B68E6A6A6291XXXX'
        ]);

        $this->assertTrue($response->isValidationFailure());
    }
}