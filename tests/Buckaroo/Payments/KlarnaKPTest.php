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

use Tests\Buckaroo\BuckarooTestCase;

class KlarnaKPTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_klarnakp_reserve()
    {
        $response = $this->buckaroo->method('klarnakp')->reserve($this->getPaymentPayload());

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_klarnakp_payment()
    {
        $response = $this->buckaroo->method('klarnakp')->pay($this->getPaymentPayload([
            'reservationNumber' => 'f055e53d-6da2-4f90-945e-73e65fa391ad',
        ]));

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_klarnakp_cancel_reservation()
    {
        $response = $this->buckaroo->method('klarnakp')->cancelReserve($this->getPaymentPayload([
            'reservationNumber' => 'fe65cf62-94a2-4609-a4d8-23c369969f31',
        ]));

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_klarnakp_update_reservation()
    {
        $response = $this->buckaroo->method('klarnakp')->updateReserve([
            'reservationNumber' => '3a9c8f5d-ffef-4f53-af40-2c1198539d6d',
            'invoice' => 'testinvoice 12345',
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

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_klarnakp_refund()
    {
        $response = $this->buckaroo->method('klarnakp')->refund($this->getRefundPayload([
            'originalTransactionKey' => 'FB4E1A0F4D714B19BF9272D3B826E09A',
        ]));

        $this->assertTrue($response->isSuccess());
    }

    private function getPaymentPayload(?array $additional = null): array
    {
        $payload = array_merge(
            $this->getBasePayPayload([], [
                'clientIP' => '198.162.1.1',
                'gender' => "1",
                'operatingCountry' => 'NL',
            ]),
            [
                'billing' => $this->getBillingPayload(['careOf', 'title', 'initials', 'category', 'birthDate']),
                'shipping' => $this->getShippingPayload(['careOf', 'title', 'initials', 'category', 'birthDate']),
                'articles' => $this->getArticlesPayload(),
            ]
        );

        if ($additional) {
            return array_merge($additional, $payload);
        }

        return $payload;
    }
}
