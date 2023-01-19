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

class In3Test extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_in3_payment()
    {
        $response = $this->buckaroo->method('in3')->pay($this->getPaymentPayload());

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_in3_installments_payment()
    {
        $response = $this->buckaroo->method('in3')->payInInstallments($this->getPaymentPayload());

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_afterpaydigiaccept_refund()
    {
        $response = $this->buckaroo->method('in3')->refund([
            'amountCredit' => 10,
            'invoice' => '10000480',
            'originalTransactionKey' => '9AA4C81A08A84FA7B68E6A6A6291XXXX',
        ]);

        $this->assertTrue($response->isFailed());
    }

    private function getPaymentPayload(): array
    {
        return [
            'amountDebit' => 9.5,
            'order' => uniqid(),
            'invoice' => uniqid(),
            'description' => 'This is a test order',
            'invoiceDate' => '22-01-2018',
            'customerType' => 'Company',
            'email' => 'test@buckaroo.nl',
            'phone' => [
                'mobile' => '0612345678',
            ],
            'articles' => [
                [
                    'identifier' => uniqid(),
                    'description' => 'Blue Toy Car',
                    'quantity' => '1',
                    'price' => 10.00,
                ],
            ],
            'company' => [
                'companyName' => 'My Company B.V.',
                'chamberOfCommerce' => '123456',
            ],
            'customer' => [
                'gender' => Gender::FEMALE,
                'initials' => 'J.S.',
                'lastName' => 'Aflever',
                'email' => 'billingcustomer@buckaroo.nl',
                'phone' => '0610000000',
                'culture' => 'nl-NL',
                'birthDate' => '1990-01-01',
            ],
            'address' => [
                'street' => 'Hoofdstraat',
                'houseNumber' => '2',
                'houseNumberAdditional' => 'a',
                'zipcode' => '8441EE',
                'city' => 'Heerenveen',
                'country' => 'NL',
            ],
            'subtotals' => [
                [
                    'name' => 'Korting',
                    'value' => -2.00,
                ],
                [
                    'name' => 'Betaaltoeslag',
                    'value' => 0.50,
                ],
                [
                    'name' => 'Verzendkosten',
                    'value' => 1.00,
                ],
            ],
        ];
    }
}
