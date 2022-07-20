<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Resources\Constants\Gender;
use Buckaroo\Tests\BuckarooTestCase;

class AfterpayDigiAcceptTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_afterpaydigiaccept_payment()
    {
        $response = $this->buckaroo->payment('afterpaydigiaccept')->pay($this->getPaymentPayload());

        $this->assertTrue($response->isRejected());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_afterpaydigiaccept_refund()
    {
        $response = $this->buckaroo->payment('afterpaydigiaccept')->refund([
            'amountCredit' => 10,
            'invoice' => '10000480',
            'originalTransactionKey' => '9AA4C81A08A84FA7B68E6A6A6291XXXX'
        ]);

        $this->assertTrue($response->isFailed());
    }

    private function getPaymentPayload(): array {
        return [
            'amountDebit'                   => 40.50,
            'order'                         => uniqid(),
            'invoice'                       => uniqid(),
            'b2b'                           => true,
            'addressesDiffer'               => true,
            'customerIPAddress'             => '0.0.0.0',
            'shippingCosts'                 => 0.5,
            'costCentre'                    => 'Test',
            'department'                    => 'Test',
            'establishmentNumber'           => '123456',
            'billing'       => [
                'recipient'        => [
                    'gender'                => Gender::FEMALE,
                    'initials'               => 'AB',
                    'lastName'              => 'Do',
                    'birthDate'             => carbon()->subYears(18)->format('Y-m-d'),
                    'culture'               => 'NL'
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
                    'mobile'        => '0698765433'
                ],
                'email'         => 'test@buckaroo.nl'
            ],
            'shipping'      => [
                'recipient'        => [
                    'culture'               => 'NL',
                    'gender'                => Gender::MALE,
                    'initials'              => 'YJ',
                    'lastName'              => 'Jansen',
                    'companyName'           => 'Buckaroo B.V.',
                    'birthDate'             => carbon()->subYear(20)->format('d-m-Y'),
                    'chamberOfCommerce'     => '12345678',
                    'vatNumber'              => 'NL12345678',
                ],
                'address'       => [
                    'street'                => 'Kalverstraat',
                    'houseNumber'           => '13',
                    'houseNumberAdditional' => 'b',
                    'zipcode'               => '4321EB',
                    'city'                  => 'Amsterdam',
                    'country'               => 'NL'
                ],
                'phone'         => [
                    'mobile'        => '0698765433'
                ],
                'email'         => 'test@buckaroo.nl',
            ],
            'articles'      => [
                [
                    'identifier' => uniqid(),
                    'description' => 'Blue Toy Car',
                    'price' => '10.00',
                    'quantity' => '2',
                    'vatCategory' => '1'
                ],
                [
                    'identifier' => uniqid(),
                    'description' => 'Red Toy Car',
                    'price' => '10.00',
                    'quantity' => '2',
                    'vatCategory' => '1'
                ],
            ]
        ];
    }
}