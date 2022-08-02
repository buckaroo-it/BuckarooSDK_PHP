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
        $response = $this->buckaroo->method('billink')->pay($this->getPaymentPayload());

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @test
     */
    public function it_creates_a_billink_authorize()
    {
        $response = $this->buckaroo->method('billink')->authorize($this->getPaymentPayload());

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @test
     */
    public function it_creates_a_billink_capture()
    {
        $response = $this->buckaroo->method('billink')->capture([
            'originalTransactionKey' => '74AD098CCFAA4F739FE16279B5059B6B', //Set transaction key of the transaction to capture
            'invoice' => '62905fa2650f4', //Set invoice id
            'amountDebit' => 50.30, //set amount to capture
            'articles'          => [
                [
                    'identifier' => 'Articlenumber1',
                    'description' => 'Blue Toy Car',
                    'vatPercentage' => '21',
                    'quantity' => '2',
                    'price' => '20.10',
                    'priceExcl' => 5
                ],
                [
                    'identifier' => 'Articlenumber2',
                    'description' => 'Red Toy Car',
                    'vatPercentage' => '21',
                    'quantity' => '1',
                    'price' => 10.10,
                    'priceExcl' => 5
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
        $response = $this->buckaroo->method('billink')->refund([
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
            'trackAndTrace'     => 'TR0F123456789',
            'vATNumber'         => '2',
            'billing'           => [
                'recipient'        => [
                    'category'              => 'B2C',
                    'careOf'                => 'John Smith',
                    'title'                 => 'Female',
                    'initials'              => 'JD',
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
                    'careOf'                => 'John Smith',
                    'title'                 => 'Male',
                    'initials'              => 'JD',
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
            ],
            'articles'          => [
                [
                    'identifier' => 'Articlenumber1',
                    'description' => 'Blue Toy Car',
                    'vatPercentage' => '21',
                    'quantity' => '2',
                    'price' => '20.10',
                    'priceExcl' => 5
                ],
                [
                    'identifier' => 'Articlenumber2',
                    'description' => 'Red Toy Car',
                    'vatPercentage' => '21',
                    'quantity' => '1',
                    'price' => 10.10,
                    'priceExcl' => 5
                ],
            ]
        ];
    }
}