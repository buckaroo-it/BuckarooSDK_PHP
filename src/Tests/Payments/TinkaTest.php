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

class TinkaTest extends BuckarooTestCase
{
    /**
     * @test
     */
    public function it_creates_a_tinka_payment()
    {
        $response = $this->buckaroo->method('tinka')->pay($this->getPaymentPayload());

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_tinka_refund()
    {
        $response = $this->buckaroo->method('tinka')->refund([
            'amountCredit' => 10,
            'invoice' => 'testinvoice 123',
            'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX',
        ]);

        $this->assertTrue($response->isFailed());
    }

    private function getPaymentPayload(): array
    {
        return [
            'amountDebit' => 3.5,
            'order' => uniqid(),
            'invoice' => uniqid(),
            'description' => 'This is a test order',
            'paymentMethod' => 'Credit',
            'deliveryMethod' => 'Locker',
            'deliveryDate' => '2030-01-01',
            'articles' => [
                [
                    'type' => 1,
                    'description' => 'Blue Toy Car',
                    'brand' => 'Ford Focus',
                    'manufacturer' => 'Ford',
                    'color' => 'Red',
                    'size' => 'Small',
                    'quantity' => '1',
                    'price' => '3.5',
                    'unitCode' => 'test',
                ],
            ],
            'customer' => [
                'gender' => Gender::MALE,
                'firstName' => 'Buck',
                'lastName' => 'Aroo',
                'initials' => 'BA',
                'birthDate' => '1990-01-01',
            ],
            'billing' => [
                'recipient' => [
                    'lastNamePrefix' => 'the',
                ],
                'email' => 'billingcustomer@buckaroo.nl',
                'phone' => [
                    'mobile' => '0109876543',
                ],
                'address' => [
                    'street' => 'Hoofdstraat',
                    'houseNumber' => '80',
                    'houseNumberAdditional' => 'A',
                    'zipcode' => '8441EE',
                    'city' => 'Heerenveen',
                    'country' => 'NL',
                ],
            ],
            'shipping' => [
                'recipient' => [
                    'lastNamePrefix' => 'the',
                ],
                'email' => 'billingcustomer@buckaroo.nl',
                'phone' => [
                    'mobile' => '0109876543',
                ],
                'address' => [
                    'street' => 'Hoofdstraat',
                    'houseNumber' => '80',
                    'houseNumberAdditional' => 'A',
                    'zipcode' => '8441EE',
                    'city' => 'Heerenveen',
                    'country' => 'NL',
                ],
            ],
        ];
    }
}
