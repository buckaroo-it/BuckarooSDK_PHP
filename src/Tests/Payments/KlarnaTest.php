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

    /**
     * @return void
     * @test
     */
    public function it_creates_a_klarna_payment_installment()
    {
        $response = $this->buckaroo->method('klarna')->payInInstallments($this->getPaymentInstallmentPayload());

        $this->assertTrue($response->isPendingProcessing());
    }

    private function getPaymentPayload(): array
    {
        return [
            'amountDebit' => 50.30,
            'order' => uniqid(),
            'invoice' => uniqid(),
            'billing' => [
                'recipient' => [
                    'category' => 'B2C',
                    'gender' => 'female',
                    'firstName' => 'John',
                    'lastName' => 'Do',
                    'birthDate' => '1990-01-01',
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
                    'mobile' => '0698765433',
                    'landline' => '0109876543',
                ],
                'email' => 'test@buckaroo.nl',
            ],
            'shipping' => [
                'recipient' => [
                    'category' => 'B2B',
                    'gender' => 'male',
                    'firstName' => 'John',
                    'lastName' => 'Do',
                    'birthDate' => '1990-01-01',
                ],
                'address' => [
                    'street' => 'Kalverstraat',
                    'houseNumber' => '13',
                    'houseNumberAdditional' => 'b',
                    'zipcode' => '4321EB',
                    'city' => 'Amsterdam',
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
        ];
    }

    private function getPaymentInstallmentPayload(): array
    {
        return [
            'amountDebit' => 50.30,
            'order' => uniqid(),
            'invoice' => uniqid(),
            'currency' => 'GBP',
            'billing' => [
                'recipient' => [
                    'category' => 'B2C',
                    'gender' => 'female',
                    'firstName' => 'John',
                    'lastName' => 'Do',
                    'birthDate' => '1990-01-01',
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
                    'landline' => '0109876543',
                ],
                'email' => 'test@buckaroo.nl',
            ],
            'shipping' => [
                'recipient' => [
                    'category' => 'B2B',
                    'gender' => 'male',
                    'firstName' => 'John',
                    'lastName' => 'Do',
                    'birthDate' => '1990-01-01',
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
        ];
    }
}
