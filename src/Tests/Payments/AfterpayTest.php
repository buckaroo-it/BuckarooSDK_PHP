<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Resources\Constants\RecipientCategory;
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

    /**
     * @return void
     * @test
     */
    public function it_creates_a_afterpay_authorize()
    {
        $response = $this->buckaroo->payment('afterpay')->authorize($this->getPaymentPayload());

        $this->assertTrue($response->isRejected());
    }

//    /**
//     * @return void
//     * @test
//     */
//    public function it_creates_a_afterpay_cancel_authorize()
//    {
//        $response = $this->buckaroo->payment('afterpay')->cancelAuthorize([
//            'amountCredit'              => 10,
//            'originalTransactionKey'    => 'F86579ECED1D493887ECAE7C287BXXXX',
//            'invoice'                   => 'testinvoice12345cvx'
//        ]);
//
//        $this->assertTrue($response->isRejected());
//    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_afterpay_capture()
    {
        $response = $this->buckaroo->payment('afterpay')->capture($this->getPaymentPayload([
            'originalTransactionKey'    => 'D5127080BA1D4644856FECDC560FXXXX'
        ]));

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_afterpay_refund()
    {
        $response = $this->buckaroo->payment('afterpay')->refund([
            'invoice'   => 'testinvoice 123', //Set invoice number of the transaction to refund
            'originalTransactionKey' => '4E8BD922192746C3918BF4077CXXXXXX', //Set transaction key of the transaction to refund
            'amountCredit' => 1.23
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    private function getPaymentPayload(?array $additional = null): array {
        $payload = [
            'amountDebit'       => 50.30,
            'order'             => uniqid(),
            'invoice'           => uniqid(),
            'billing'       => [
                'recipient'        => [
                    'category'      => RecipientCategory::PERSON,
                    'careOf'        => 'John Smith',
                    'title'            => 'Mrs',
                    'firstName'      => 'John',
                    'lastName'      => 'Do',
                    'birthDate'     => carbon()->subYears(18)->format('Y-m-d'),
                    'conversationLanguage'  => 'NL',
                    'identificationNumber'  => 'IdNumber12345',
                    'customerNumber'        => 'customerNumber12345'
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
            'shipping'      => [
                'recipient'        => [
                    'category'      => RecipientCategory::COMPANY,
                    'careOf'        => 'John Smith',
                    'companyName'   => 'Buckaroo B.V.',
                    'firstName'      => 'John',
                    'lastName'          => 'Do',
                    'chamberOfCommerce' => '12345678'
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

        if($additional)
        {
            return array_merge($additional, $payload);
        }

        return $payload;
    }
}