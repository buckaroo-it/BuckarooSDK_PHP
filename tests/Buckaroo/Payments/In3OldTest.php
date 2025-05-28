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
use Buckaroo\Resources\Constants\Gender;

class In3OldTest extends BuckarooTestCase
{
    // // 491 - Action Pay is no longer available for Capayable
    // /**
    //  * @return void
    //  * @test
    //  */
    // public function it_creates_a_in3old_payment()
    // {
    //     $response = $this->buckaroo->method('in3old')->pay($this->getPaymentPayload());

    //     $this->assertTrue($response->isSuccess());
    // }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_in3old_installments_payment()
    {
        $response = $this->buckaroo->method('in3Old')->payInInstallments($this->getPaymentPayload());

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_in3old_refund()
    {
        $response = $this->buckaroo->method('in3Old')->refund($this->getRefundPayload([
            'originalTransactionKey' => 'C1311F77EB3F48CDB5774F7C6842FE12',
        ]));

        $this->assertTrue($response->isSuccess());
    }

    private function getPaymentPayload(): array
    {
        return $this->getPayPayload(
            [
                'invoiceDate' => '22-01-2018',
                'customerType' => 'Company',
                'articles' => $this->getArticlesPayload(['vatPercentage']),
                'email' => 'test@buckaroo.nl',
                'phone' => [
                    'mobile' => '0612345678',
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
            ]
        );
    }
}
