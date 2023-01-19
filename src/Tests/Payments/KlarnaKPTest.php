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

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class KlarnaKPTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_klarnakp_payment()
    {
        $response = $this->buckaroo->method('klarnakp')->pay([
            'amountDebit' => 50.30,
            'order' => uniqid(),
            'invoice' => uniqid(),
            'reservationNumber' => '2377577452',
            'serviceParameters' => [
                'articles' => [
                    [
                        'identifier' => uniqid(),
                        'quantity' => '2',
                    ],
                    [
                        'identifier' => uniqid(),
                        'quantity' => '2',
                    ],
                ],
            ],
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_klarnakp_reserve()
    {
        $response = $this->buckaroo->method('klarnakp')->reserve([
            'invoice' => uniqid(),
            'gender' => "1",
            'operatingCountry' => 'NL',
            'pno' => '01011990',
            'billing' => [
                'recipient' => [
                    'firstName' => 'John',
                    'lastName' => 'Do',
                ],
                'address' => [
                    'street' => 'Neherkade',
                    'houseNumber' => '1',
                    'zipcode' => '2521VA',
                    'city' => 'Gravenhage',
                    'country' => 'NL',
                ],
                'phone' => [
                    'mobile' => '0612345678',
                ],
                'email' => 'youremail@example.nl',
            ],
            'shipping' => [
                'recipient' => [
                    'firstName' => 'John',
                    'lastName' => 'Do',
                ],
                'address' => [
                    'street' => 'Rosenburglaan',
                    'houseNumber' => '216',
                    'zipcode' => '4385 JM',
                    'city' => 'Vlissingen',
                    'country' => 'NL',
                ],
                'email' => 'test@buckaroo.nl',
            ],
            'articles' => [
                [
                    'identifier' => 'Articlenumber1',
                    'description' => 'Blue Toy Car',
                    'vatPercentage' => '21',
                    'quantity' => '2',
                    'price' => '20.10',
                ],
                [
                    'identifier' => 'Articlenumber2',
                    'description' => 'Red Toy Car',
                    'vatPercentage' => '21',
                    'quantity' => '1',
                    'price' => '10.10',
                ],
            ],
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_klarnakp_cancel_reservation()
    {
        $response = $this->buckaroo->method('klarnakp')->cancelReserve([
            'reservationNumber' => '2377577452',
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_klarnakp_update_reservation()
    {
        $response = $this->buckaroo->method('klarnakp')->updateReserve([
            'invoice' => 'testinvoice 1234',
            'billing' => [
                'recipient' => [
                    'careOf' => 'Person',
                    'firstName' => 'John',
                    'lastName' => 'Do',
                ],
                'address' => [
                    'street' => 'Hoofdstraat',
                    'houseNumber' => '13',
                    'houseNumberAdditional' => 'a',
                    'zipcode' => '1234AB',
                    'city' => 'Heerenveen',
                    'country' => 'GB',
                ],
                'phone' => [
                    'mobile' => '0698765433',
                    'landLine' => '0109876543',
                ],
                'email' => 'test@buckaroo.nl',
            ],
            'shipping' => [
                'recipient' => [
                    'careOf' => 'Company',
                    'firstName' => 'John',
                    'lastName' => 'Do',
                ],
                'address' => [
                    'street' => 'Kalverstraat',
                    'houseNumber' => '13',
                    'houseNumberAdditional' => 'b',
                    'zipcode' => '4321EB',
                    'city' => 'Amsterdam',
                    'country' => 'GB',
                ],
                'email' => 'test@buckaroo.nl',
            ],
            'articles' => [
                [
                    'identifier' => 'Articlenumber1',
                    'description' => 'Blue Toy Car',
                    'vatPercentage' => '21',
                    'quantity' => '2',
                    'price' => '20.10',
                ],
                [
                    'identifier' => 'Articlenumber2',
                    'description' => 'Red Toy Car',
                    'vatPercentage' => '21',
                    'quantity' => '1',
                    'price' => '10.10',
                ],
            ],
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_klarnakp_refund()
    {
        $response = $this->buckaroo->method('klarnakp')->refund([
            'amountCredit' => 10,
            'invoice' => '10000480',
            'originalTransactionKey' => '9AA4C81A08A84FA7B68E6A6A6291XXXX',
        ]);

        $this->assertTrue($response->isValidationFailure());
    }
}
