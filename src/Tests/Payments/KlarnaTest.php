<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Resources\Constants\Gender;
use Buckaroo\Tests\BuckarooTestCase;

class KlarnaTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_klarna_payment()
    {
        $response = $this->buckaroo->method('klarna')->pay($this->getPaymentPayload());

        $this->assertTrue($response->isPendingProcessing());
    }

    private function getPaymentPayload(): array {
        return [
            'amountDebit'       => 50.30,
            'order'             => uniqid(),
            'invoice'           => uniqid(),
            'billing'           => [
                'recipient'        => [
                    'category'              => 'B2C',
                    'gender'                 => 'female',
                    'firstName'             => 'John',
                    'lastName'              => 'Do',
                    'birthDate'             => carbon()->subYears(18)->format('d-m-Y')
                ],
                'address'       => [
                    'street'                => 'Hoofdstraat',
                    'houseNumber'           => '13',
                    'houseNumberAdditional' => 'a',
                    'zipcode'               => '1234AB',
                    'city'                  => 'Heerenveen',
                    'country'               => 'NL'
                ],
                'phone'         => [
                    'mobile'        => '0698765433',
                    'landline'      => '0109876543'
                ],
                'email'         => 'test@buckaroo.nl'
            ],
            'shipping'          => [
                'recipient'        => [
                    'category'              => 'B2B',
                    'gender'                 => 'male',
                    'firstName'             => 'John',
                    'lastName'              => 'Do',
                    'birthDate'             => carbon()->subYears(20)->format('d-m-Y')
                ],
                'address'       => [
                    'street'                => 'Kalverstraat',
                    'houseNumber'           => '13',
                    'houseNumberAdditional' => 'b',
                    'zipcode'               => '4321EB',
                    'city'                  => 'Amsterdam',
                    'country'               => 'NL'
                ],
                'email'         => 'test@buckaroo.nl'
            ],
            'articles'      => [
                [
                    'identifier' => 'Articlenumber1',
                    'description' => 'Blue Toy Car',
                    'vatPercentage' => '21',
                    'quantity' => '2',
                    'price' => '20.10'
                ],
                [
                    'identifier' => 'Articlenumber2',
                    'description' => 'Red Toy Car',
                    'vatPercentage' => '21',
                    'quantity' => '1',
                    'price' => '10.10'
                ],
            ]
        ];
    }
}