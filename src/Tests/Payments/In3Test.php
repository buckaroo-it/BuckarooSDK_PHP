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
        $response = $this->buckaroo->method('in3')->pay($this->getPaymentPayload());

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_in3_installments_payment()
    {
        $response = $this->buckaroo->method('in3')->payInInstallments($this->getPaymentPayload());

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_afterpaydigiaccept_refund()
    {
        $response = $this->buckaroo->method('in3')->refund([
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
            'invoiceDate'       => '22-01-2018',
            'customerType'      => 'Company',
            'email'             => 'test@buckaroo.nl',
            'phone'             => [
                'mobile'        => '0612345678'
            ],
            'articles'      => [
                [
                    'identifier'        => uniqid(),
                    'description'       => 'Blue Toy Car',
                    'quantity'          => '1',
                    'price'             => 10.00
                ]
            ],
            'company'       => [
                'companyName'       => 'My Company B.V.',
                'chamberOfCommerce' => '123456'
            ],
            'customer'      => [
                'gender'                => Gender::FEMALE,
                'initials'              => 'J.S.',
                'lastName'              => 'Aflever',
                'email'                 => 'billingcustomer@buckaroo.nl',
                'phone'                 => '0610000000',
                'culture'               => 'nl-NL',
                'birthDate'             => carbon()->subYears(20)->format('Y-m-d'),
            ],
            'address'   => [
                'street'                => 'Hoofdstraat',
                'houseNumber'           => '2',
                'houseNumberAdditional' => 'a',
                'zipcode'               => '8441EE',
                'city'                  => 'Heerenveen',
                'country'               => 'NL'
            ],
            'subtotals'      => [
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
        ];
    }
}