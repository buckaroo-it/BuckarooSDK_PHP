<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class AfterpayTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_afterpay_payment()
    {
        $response = $this->buckaroo->payment('afterpay')->pay($this->getPaymentPayload());

        $this->assertTrue($response->isRejected());
    }

    private function getPaymentPayload(): array {
        return [
            'amountDebit'       => 50.30,
            'order'             => uniqid(),
            'invoice'           => uniqid(),
            'serviceParameters' => [
                'articles'      => [
                    [
                        'identifier' => 'Articlenumber1',
                        'description' => 'Blue Toy Car',
                        'vatPercentage' => '21',
                        'quantity' => '2',
                        'grossUnitPrice' => '20.10'
                    ],
                    [
                        'identifier' => 'Articlenumber2',
                        'description' => 'Red Toy Car',
                        'vatPercentage' => '21',
                        'quantity' => '1',
                        'grossUnitPrice' => '10.10'
                    ],
                ],
                'customer'      => [
                    'useBillingInfoForShipping' => false,
                    'billing'                   => [
                        'category'  => 'Person',
                        'firstName' => 'Test',
                        'lastName' => 'Acceptatie',
                        'email' => 'billingcustomer@buckaroo.nl',
                        'phone' => '0109876543',
                        'street' => 'Hoofdstraat',
                        'streetNumber' => '80',
                        'streetNumberAdditional' => 'A',
                        'postalCode' => '8441EE',
                        'city' => 'Heerenveen',
                        'country' => 'NL',
                        'salutation' => 'Mr',
                        'birthDate' => '01-01-1990'
                    ],
                    'shipping'                  => [
                        'category'  => 'Person',
                        'firstName' => 'Test',
                        'lastName' => 'Aflever',
                        'email' => 'billingcustomer@buckaroo.nl',
                        'phone' => '0109876543',
                        'street' => 'Hoofdstraat',
                        'streetNumber' => '80',
                        'streetNumberAdditional' => 'A',
                        'postalCode' => '8441EE',
                        'city' => 'Heerenveen',
                        'country' => 'NL',
                        'salutation' => 'Mr',
                        'birthDate' => '01-01-1990'
                    ]
                ]
            ]
        ];
    }
}