<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Resources\Constants\Gender;
use Buckaroo\Tests\BuckarooTestCase;

class In3Test extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_in3_payment()
    {
        $response = $this->buckaroo->payment('in3')->pay($this->getPaymentPayload());

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_in3_installments_payment()
    {
        $response = $this->buckaroo->payment('in3')->payInInstallments($this->getPaymentPayload());

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_afterpaydigiaccept_refund()
    {
        $response = $this->buckaroo->payment('in3')->refund([
            'amountCredit' => 10,
            'invoice' => '10000480',
            'originalTransactionKey' => '9AA4C81A08A84FA7B68E6A6A6291XXXX'
        ]);

        $this->assertTrue($response->isFailed());
    }

    private function getPaymentPayload(): array
    {
        return [
            'amountDebit'       => 9.5,
            'order'             => uniqid(),
            'invoice'           => uniqid(),
            'description'       => 'This is a test order',
            'serviceParameters' => [
                'invoiceDate'       => '22-01-2018',
                'customerType'      => 'Company',
                'articles'      => [
                    [
                        'identifier'        => uniqid(),
                        'description'       => 'Blue Toy Car',
                        'quantity'          => '1',
                        'price'             => 10.00
                    ]
                ],
                'company'       => [
                    'name'      => 'My Company B.V.',
                    'chamberOfCommerce' => '123456'
                ],
                'customer'      => [
                    'gender'        => '1',
                    'initials'      => 'J.S.',
                    'firstName' => 'Test',
                    'lastName' => 'Aflever',
                    'email' => 'billingcustomer@buckaroo.nl',
                    'phone' => '0610000000',
                    'birthDate' => '01-01-1990',
                    'address'   => [
                        'street' => 'Hoofdstraat',
                        'housenumber'   => '2',
                        'streetNumberAdditional' => 'a',
                        'postalCode' => '8441EE',
                        'city' => 'Heerenveen',
                        'country'=> 'NL'
                    ]
                ],
                'subtotal'      => [
                    [
                        'name'      => 'Korting',
                        'value'     => -2.00
                    ],
                    [
                        'name'      => 'Betaaltoeslag',
                        'value'     => 0.50
                    ],
                    [
                        'name'      => 'Verzendkosten',
                        'value'     => 1.00
                    ]
                ]
            ]
        ];
    }
}