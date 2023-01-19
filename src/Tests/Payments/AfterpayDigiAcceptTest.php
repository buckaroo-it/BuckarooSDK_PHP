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
        $response = $this->buckaroo->method('afterpaydigiaccept')->pay($this->getPaymentPayload());

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_afterpaydigiaccept_authorize()
    {
        $response = $this->buckaroo->method('afterpaydigiaccept')->authorize($this->getPaymentPayload());

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_afterpaydigiaccept_capture()
    {
        $response = $this->buckaroo->method('afterpaydigiaccept')->capture($this->getPaymentPayload([
            'originalTransactionKey' => '9AA4C81A08A84FA7B68E6A6A6291XXXX',
        ]));

        $this->assertTrue($response->isFailed());
    }

//    /**
//     * @return void
//     * @test
//     */
//    public function it_creates_a_afterpaydigiaccept_cancel_authorize()
//    {
//        $response = $this->buckaroo->method('afterpaydigiaccept')->cancelAuthorize([
//            'amountCredit' => 10,
//            'invoice' => '10000480',
//            'originalTransactionKey' => '9AA4C81A08A84FA7B68E6A6A6291XXXX'
//        ]);
//
//        $this->assertTrue($response->isFailed());
//    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_afterpaydigiaccept_refund()
    {
        $response = $this->buckaroo->method('afterpaydigiaccept')->refund([
            'amountCredit' => 10,
            'invoice' => '10000480',
            'originalTransactionKey' => '9AA4C81A08A84FA7B68E6A6A6291XXXX',
        ]);

        $this->assertTrue($response->isFailed());
    }

    private function getPaymentPayload(array $additionalParameters = null): array
    {
        $payload = [
            'amountDebit' => 40.50,
            'order' => uniqid(),
            'invoice' => uniqid(),
            'b2b' => true,
            'addressesDiffer' => true,
            'customerIPAddress' => '0.0.0.0',
            'shippingCosts' => 0.5,
            'costCentre' => 'Test',
            'department' => 'Test',
            'establishmentNumber' => '123456',
            'billing' => [
                'recipient' => [
                    'gender' => Gender::FEMALE,
                    'initials' => 'AB',
                    'lastName' => 'Do',
                    'birthDate' => '1990-01-01',
                    'culture' => 'NL',
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
                ],
                'email' => 'test@buckaroo.nl',
            ],
            'shipping' => [
                'recipient' => [
                    'culture' => 'NL',
                    'gender' => Gender::MALE,
                    'initials' => 'YJ',
                    'lastName' => 'Jansen',
                    'companyName' => 'Buckaroo B.V.',
                    'birthDate' => '1990-01-01',
                    'chamberOfCommerce' => '12345678',
                    'vatNumber' => 'NL12345678',
                ],
                'address' => [
                    'street' => 'Kalverstraat',
                    'houseNumber' => '13',
                    'houseNumberAdditional' => 'b',
                    'zipcode' => '4321EB',
                    'city' => 'Amsterdam',
                    'country' => 'NL',
                ],
                'phone' => [
                    'mobile' => '0698765433',
                ],
                'email' => 'test@buckaroo.nl',
            ],
            'articles' => [
                [
                    'identifier' => uniqid(),
                    'description' => 'Blue Toy Car',
                    'price' => '10.00',
                    'quantity' => '2',
                    'vatCategory' => '1',
                ],
                [
                    'identifier' => uniqid(),
                    'description' => 'Red Toy Car',
                    'price' => '10.00',
                    'quantity' => '2',
                    'vatCategory' => '1',
                ],
            ],
        ];

        if ($additionalParameters)
        {
            $payload = array_merge($payload, $additionalParameters);
        }

        return $payload;
    }
}
