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

class SubscriptionsTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_subscription()
    {
        $response = $this->buckaroo->method('subscriptions')->create([
            'startDate' => date('Y-m-d'),
            'ratePlans' => [
                'add' => [
                    'startDate' => date('Y-m-d'),
                    'billingTiming' => 1,
                    'ratePlanName' => 'Test Rate Plan',
                    'ratePlanDescription' => 'Test Rate Plan',
                    'currency' => 'EUR',
                    'billingInterval' => 'Weekly',
                    'termStartDay' => '1',
                ],
            ],
            'ratePlanCharges' => [
                'add' => [
                    'ratePlanChargeName' => 'Rate Plan Charge',
                    'rateplanChargeDescription' => 'Rate Plan Charge Description',
                    'unitOfMeasure' => 'Quantity',
                    'baseNumberOfUnits' => '1',
                    'partialBilling' => 'Billfull',
                    'pricePerUnit' => '2',
                    'priceIncludesVat' => true,
                    'vatPercentage' => '21',
                    'ratePlanChargeType' => 'Recurring',
                ],
            ],
            'debtor' => [
                'code' => 'johnsmith4',
            ],
        ]);

        self::$payTransactionKey = $response->getServiceParameters()['subscriptionguid'];

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_combined_subscription()
    {
        $subscriptions = $this->buckaroo->method('subscriptions')->manually()->createCombined([
            'pushURL' => 'https://example.com/buckaroo/push',
            'includeTransaction' => false,
            'transactionVatPercentage' => 5,
            'configurationCode' => '7esem6f7',
            'email' => 'test@buckaroo.nl',
            'ratePlans' => [
                'add' => [
                    'startDate' => date('Y-m-d'),
                    'ratePlanCode' => '9863hdcj',
                ],
            ],
            'phone' => [
                'mobile' => '0612345678',
            ],
            'debtor' => [
                'code' => 'johnsmith4',
            ],
            'company' => [
                'culture' => 'nl-NL',
                'companyName' => 'My Company Coporation',
                'vatApplicable' => true,
                'vatNumber' => 'NL140619562B01',
                'chamberOfCommerce' => '20091741',
            ],
            'address' => [
                'street' => 'Hoofdstraat',
                'houseNumber' => '90',
                'houseNumberAdditional' => 'a',
                'zipcode' => '8441ER',
                'city' => 'Heerenveen',
                'country' => 'NL',
            ],
        ]);

        $response = $this->buckaroo->method('ideal')->combine($subscriptions)->pay([
            'startRecurrent' => true,
            'invoice' => uniqid(),
            'amountDebit' => 10.10,
            'issuer' => 'ABNANL2A',
        ]);

        $this->assertTrue($response->isWaitingOnUserInput());
    }

    /**
     * @return void
     * @test
     */
    public function it_updates_subscription()
    {
        $response = $this->buckaroo->method('subscriptions')->update([
            'subscriptionGuid' => self::$payTransactionKey,
            'configurationCode' => '7esem6f7',
            'ratePlans' => [
                'update' => [
                    'ratePlanGuid' => '56CC308A1D694CF19F808993DD42BE7B',
                    'endDate' => '2030-01-01',
                    'charge' => [
                        'ratePlanChargeGuid' => '15C2CEEB39E34C86AAD0038ED73807B0',
                        'baseNumberOfUnits' => '1',
                        'pricePerUnit' => 5,
                    ],
                ],
            ],
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_updates_combined_subscription()
    {
        $subscription = $this->buckaroo->method('subscriptions')->manually()->updateCombined([
            'startRecurrent' => true,
            'subscriptionGuid' => '36F17939D56549BD91C64A00FAE8161A',
        ]);

        $response = $this->buckaroo->method('ideal')->combine($subscription)->pay([
            'invoice' => uniqid(),
            'amountDebit' => 10.10,
            'issuer' => 'ABNANL2A',
        ]);


        $this->assertTrue($response->isWaitingOnUserInput());
    }

    /**
     * @return void
     * @depends it_creates_a_subscription
     * @test
     */
    public function it_stops_subscription()
    {
        $response = $this->buckaroo->method('subscriptions')->stop([
            'subscriptionGuid' => self::$payTransactionKey,
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @depends it_creates_a_subscription
     * @test
     */
    public function it_get_info_of_subscription()
    {
        $response = $this->buckaroo->method('subscriptions')->info([
            'subscriptionGuid' => self::$payTransactionKey,
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @depends it_creates_a_subscription
     * @test
     */
    public function it_delete_payment_config_of_subscription()
    {
        $response = $this->buckaroo->method('subscriptions')->deletePaymentConfig([
            'subscriptionGuid' => self::$payTransactionKey,
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @depends it_creates_a_subscription
     * @test
     */
    public function it_pauses_of_subscription()
    {
        $response = $this->buckaroo->method('subscriptions')->pause([
            'resumeDate' => '2030-01-01',
            'subscriptionGuid' => self::$payTransactionKey,
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @depends it_creates_a_subscription
     * @test
     */
    public function it_resumes_of_subscription()
    {
        $response = $this->buckaroo->method('subscriptions')->resume([
            'resumeDate' => '2030-01-01',
            'subscriptionGuid' => self::$payTransactionKey,
        ]);

        $this->assertTrue($response->isSuccess());
    }
}
