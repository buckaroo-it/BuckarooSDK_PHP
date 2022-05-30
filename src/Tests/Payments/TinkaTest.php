<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class TinkaTest extends BuckarooTestCase
{
    /**
     * @test
     */
    public function it_creates_a_tinkal_payment()
    {
        $response = $this->buckaroo->payment('tinka')->pay($this->getPaymentPayload());

        dd($response);
        $this->assertTrue($response->isPendingProcessing());
    }

    private function getPaymentPayload(): array
    {
        return [
            'amountDebit'       => 9.5,
            'order'             => uniqid(),
            'invoice'           => uniqid(),
            'description'       => 'This is a test order',
            'serviceParameters' => [
                'paymentMethod'       => 'Credit',
                'deliveryMethod'      => 'Locker',
                'deliveryDate'          => '09-12-2022',
                'articles'      => [
                    [
//                        'type'              => 1,
//                        'description'       => 'Blue Toy Car',
//                        'brand'             => 'Ford Focus',
//                        'manufacturer'      => 'Ford',
//                        'color'             => 'Red',
//                        'size'              => 'Small',
//                        'quantity'          => '1',
                        'grossUnitPrice'    => function($test){
                            return 'aiosdf';
                        },
//                        'unitCode'         => 'test'
                    ]
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