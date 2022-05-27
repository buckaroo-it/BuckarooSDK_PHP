<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class BillinkTest extends BuckarooTestCase
{
    /**
     * @test
     */
    public function it_creates_a_billink_payment()
    {
        $response = $this->buckaroo->payment('billink')->pay($this->getPaymentPayload());

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @test
     */
    public function it_creates_a_billink_authorize()
    {
        $response = $this->buckaroo->payment('billink')->authorize($this->getPaymentPayload());

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @test
     */
    public function it_creates_a_billink_capture()
    {
        $response = $this->buckaroo->payment('billink')->capture([
            'originalTransactionKey' => '74AD098CCFAA4F739FE16279B5059B6B', //Set transaction key of the transaction to capture
            'invoice' => '62905fa2650f4', //Set invoice id
            'amountDebit' => 50.30, //set amount to capture
            'serviceParameters' => [
                'articles'      => [
                    [
                        'identifier' => 'Articlenumber1',
                        'description' => 'Blue Toy Car',
                        'vatPercentage' => '21',
                        'quantity' => '2',
                        'grossUnitPriceIncl' => '20.10',
                        'grossUnitPriceExcl' => '15'
                    ],
                    [
                        'identifier' => 'Articlenumber2',
                        'description' => 'Red Toy Car',
                        'vatPercentage' => '21',
                        'quantity' => '1',
                        'grossUnitPriceIncl' => '10.10',
                        'grossUnitPriceExcl' => '5'
                    ],
                ],
            ]
        ]);

        $this->assertTrue($response->isRejected());
    }

    /**
     * @test
     */
    public function it_creates_a_billink_refund()
    {
        $response = $this->buckaroo->payment('billink')->refund([
            'amountCredit' => 10,
            'invoice'       => 'testinvoice 123',
            'originalTransactionKey' => '4E8BD922192746C3918BF4077CXXXXXX'
        ]);

        $this->assertTrue($response->isFailed());
    }

    private function getPaymentPayload(): array {
        return [
            'amountDebit'       => 50.30,
            'order'             => uniqid(),
            'invoice'           => uniqid(),
            'serviceParameters' => [
                'trackAndTrace' => 'TR0F123456789',
                'vatNumber'     => '2',
                'articles'      => [
                    [
                        'identifier' => 'Articlenumber1',
                        'description' => 'Blue Toy Car',
                        'vatPercentage' => '21',
                        'quantity' => '2',
                        'grossUnitPriceIncl' => '20.10',
                        'grossUnitPriceExcl' => '15'
                    ],
                    [
                        'identifier' => 'Articlenumber2',
                        'description' => 'Red Toy Car',
                        'vatPercentage' => '21',
                        'quantity' => '1',
                        'grossUnitPriceIncl' => '10.10',
                        'grossUnitPriceExcl' => '5'
                    ],
                ],
                'customer'      => [
                    'useBillingInfoForShipping' => false,
                    'billing'                   => [
                        'careOf'        => 'John Smith',
                        'initials'  => 'T',
                        'salutation' => 'Male',
                        'firstName' => 'Test',
                        'lastName' => 'Acceptatie',
                        'chamberOfCommerce' => 'Kvk123456789',
                        'email' => 'billingcustomer@buckaroo.nl',
                        'street' => 'Hoofdstraat',
                        'streetNumber' => '80',
                        'streetNumberAdditional' => 'A',
                        'postalCode' => '8441EE',
                        'city' => 'Heerenveen',
                        'country' => 'NL',
                        'mobilePhone' => '0698765433',
                        'birthDate' => '01-01-1990'
                    ],
                    'shipping'                  => [
                        'careOf'        => 'John Smith',
                        'initials'  => 'T',
                        'firstName' => 'Test',
                        'lastName' => 'Aflever',
                        'email' => 'billingcustomer@buckaroo.nl',
                        'street' => 'Hoofdstraat',
                        'streetNumber' => '80',
                        'streetNumberAdditional' => 'A',
                        'postalCode' => '8441EE',
                        'city' => 'Heerenveen',
                        'country' => 'NL',
                        'mobilePhone' => '0698765433',
                        'salutation' => 'Male',
                        'birthDate' => '01-01-1990'
                    ]
                ]
            ]
        ];
    }
}