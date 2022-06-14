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

        $this->assertTrue($response->isSuccess());
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
            'billingGender'                 => Gender::FEMALE,
            'billingInitials'               => 'AB',
            'billingLastName'               => 'Test',
            'billingBirthDate'              => carbon()->subYear(18)->format('d-m-Y'),
            'billingStreet'                 => 'Hoofdstraat',
            'billingHouseNumber'            => '1',
            'billingPostalCode'             => '8441ER',
            'billingCity'                   => 'Heerenveen',
            'billingCountry'                => 'NL',
            'billingEmail'                  => 'xxxxx@xxx.nl',
            'billingPhoneNumber'            => '0612345678',
            'billingLanguage'               => 'NL',
            'addressesDiffer'               => true,
            'shippingGender'                => Gender::MALE,
            'shippingInitials'              => 'YJ',
            'shippingLastName'              => 'Jansen',
            'shippingBirthDate'             => carbon()->subYear(20)->format('d-m-Y'),
            'shippingStreet'                => 'Hoofdstraat',
            'shippingHouseNumber'           => '2',
            'shippingPostalCode'            => '8441ER',
            'shippingCity'                  => 'Heerenveen',
            'shippingCountryCode'           => 'NL',
            'shippingEmail'                 => 'xxxxx@xxx.nl',
            'shippingPhoneNumber'           => '0612345678',
            'shippingLanguage'              => 'NL',
            'shippingCosts'                 => 0.5,
            'customerIPAddress'             => '0.0.0.0',
            'companyCOCRegistration'        => '12356',
            'companyName'                   => 'Buckaroo BV',
            'costCentre'                    => 'Test',
            'department'                    => 'Test',
            'establishmentNumber'           => '123456',
            'vatNumber'                     => 'NL12345678',
            'articles'      => [
                [
                    'identifier' => uniqid(),
                    'description' => 'Blue Toy Car',
                    'unitPrice' => '10.00',
                    'quantity' => '2',
                    'vatCategory' => '1'
                ],
                [
                    'identifier' => uniqid(),
                    'description' => 'Red Toy Car',
                    'unitPrice' => '10.00',
                    'quantity' => '2',
                    'vatCategory' => '1'
                ],
            ]
        ];
    }
}