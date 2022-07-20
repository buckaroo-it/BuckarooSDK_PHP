<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Resources\Constants\Gender;
use Buckaroo\Tests\BuckarooTestCase;

class TinkaTest extends BuckarooTestCase
{
    /**
     * @test
     */
    public function it_creates_a_tinka_payment()
    {
        $response = $this->buckaroo->payment('tinka')->pay($this->getPaymentPayload());

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_tinka_refund()
    {
        $response = $this->buckaroo->payment('tinka')->refund([
            'amountCredit' => 10,
            'invoice'       => 'testinvoice 123',
            'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX'
        ]);

        $this->assertTrue($response->isFailed());
    }

    private function getPaymentPayload(): array
    {
        return [
            'amountDebit'           => 3.5,
            'order'                 => uniqid(),
            'invoice'               => uniqid(),
            'description'           => 'This is a test order',
            'paymentMethod'         => 'Credit',
            'deliveryMethod'        => 'Locker',
            'deliveryDate'          => carbon()->addDays(30)->format('Y-m-d'),
            'articles'              => [
                [
                    'type'              => 1,
                    'description'       => 'Blue Toy Car',
                    'brand'             => 'Ford Focus',
                    'manufacturer'      => 'Ford',
                    'color'             => 'Red',
                    'size'              => 'Small',
                    'quantity'          => '1',
                    'price'             => '3.5',
                    'unitCode'         => 'test'
                ]
            ],
            'customer'          => [
                'gender'        => Gender::MALE,
                'firstName'     => 'Buck',
                'lastName'      => 'Aroo',
                'initials'       => 'BA',
                'birthDate'     => carbon()->subYears(18)->format('Y-m-d'),
            ],
            'billing'              => [
                'recipient'          => [
                    'lastNamePrefix'    => 'the'
                ],
                'email' => 'billingcustomer@buckaroo.nl',
                'phone' => [
                    'mobile' => '0109876543',
                ],
                'address'                   => [
                    'street' => 'Hoofdstraat',
                    'houseNumber' => '80',
                    'houseNumberAdditional' => 'A',
                    'zipcode' => '8441EE',
                    'city' => 'Heerenveen',
                    'country' => 'NL',
                ]
            ],
            'shipping'              => [
                'recipient'          => [
                    'lastNamePrefix'    => 'the'
                ],
                'email' => 'billingcustomer@buckaroo.nl',
                'phone' => [
                    'mobile' => '0109876543',
                ],
                'address'                   => [
                    'street' => 'Hoofdstraat',
                    'houseNumber' => '80',
                    'houseNumberAdditional' => 'A',
                    'zipcode' => '8441EE',
                    'city' => 'Heerenveen',
                    'country' => 'NL',
                ]
            ]
        ];
    }
}