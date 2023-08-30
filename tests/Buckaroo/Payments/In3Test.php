<?php
/*
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * It is available through the world-wide-web at this URL:
 * https://tldrlegal.com/license/mit-license
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to support@buckaroo.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact support@buckaroo.nl for more information.
 *
 * @copyright Copyright (c) Buckaroo B.V.
 * @license   https://tldrlegal.com/license/mit-license
 */

namespace Tests\Buckaroo\Payments;

use Buckaroo\Resources\Constants\RecipientCategory;
use Tests\Buckaroo\BuckarooTestCase;
use Buckaroo\Resources\Constants\Gender;

class In3Test extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_in3_payment()
    {
        $response = $this->buckaroo->method('in3')->pay($this->getPaymentPayload());

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_in3_refund()
    {
        $response = $this->buckaroo->method('in3Old')->refund([
            'amountCredit' => 10,
            'invoice' => '10000480',
            'originalTransactionKey' => '9AA4C81A08A84FA7B68E6A6A6291XXXX',
        ]);

        $this->assertTrue($response->isFailed());
    }

    private function getPaymentPayload(?array $additional = null): array
    {
        $payload = [
            'amountDebit'       => 52.30,
            'description'       => 'in3 pay',
            'order'             => uniqid(),
            'invoice'           => uniqid(),
            'clientIP'      => '127.0.0.1',
            'billing'       => [
                'recipient'        => [
                    'category'      => 'B2C',
                    'initials'      => 'J',
                    'firstName'      => 'John',
                    'lastName'      => 'Dona',
                    'birthDate'     => '1990-01-01',
                    'customerNumber'        => '12345',
                    'phone'                 => '0612345678',
                    'country'               => 'NL',
                    'companyName' => 'My Company B.V.',
                    'chamberOfCommerce' => '123456'
                ],
                'address' => [
                    'street' => 'Hoofdstraat',
                    'houseNumber' => '13',
                    'houseNumberAdditional' => 'a',
                    'zipcode' => '1234AB',
                    'city' => 'Heerenveen',
                    'country' => 'NL',
                ],
                'phone' => [
                    'phone' => '0698765433',
                ],
                'email' => 'test@buckaroo.nl',
            ],
            'shipping' => [
                'recipient' => [
                    'category' => 'B2C',
                    'careOf' => 'John Smith',
                    'firstName' => 'John',
                    'lastName' => 'Do',
                    'chamberOfCommerce' => '123456'
                ],
                'address' => [
                    'street' => 'Kalverstraat',
                    'houseNumber' => '13',
                    'houseNumberAdditional' => 'b',
                    'zipcode' => '4321EB',
                    'city' => 'Amsterdam',
                    'country' => 'NL',
                ],
            ],
            'articles' => [
                [
                    'identifier' => 'Articlenumber1',
                    'type' => 'Physical',
                    'description' => 'Blue Toy Car',
                    'category' => 'test product',
                    'vatPercentage' => '21',
                    'quantity' => '2',
                    'price' => '20.10',
                ],
                [
                    'identifier' => 'Articlenumber2',
                    'type' => 'Physical',
                    'description' => 'Red Toy Car',
                    'category' => 'test product',
                    'vatPercentage' => '21',
                    'quantity' => '1',
                    'price' => '10.10',
                ],
                [
                    'identifier' => 'USPShippingID',
                    'type' => 'Physical',
                    'description' => 'UPS',
                    'category' => 'test product',
                    'vatPercentage' => '21',
                    'quantity' => '1',
                    'price' => '2',
                ],
            ]
        ];

        if ($additional)
        {
            return array_merge($additional, $payload);
        }

        return $payload;
    }
}
