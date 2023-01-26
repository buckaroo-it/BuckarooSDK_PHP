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

class SubscriptionsTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_subscription()
    {
        $response = $this->buckaroo->method('subscriptions')->create([
            'ratePlans' => [
                'add' => [
                    'startDate' => '2023-01-01',
                    'ratePlanCode' => 'xxxxxx',
                ],
            ],
            'configurationCode' => 'xxxxx',
            'debtor' => [
                'code' => 'xxxxxx',
            ],
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_combined_subscription()
    {
        $subscriptions = $this->buckaroo->method('subscriptions')->manually()->createCombined([
            'pushURL' => 'https://buckaroo.nextto.dev/push',
            'includeTransaction' => false,
            'transactionVatPercentage' => 5,
            'configurationCode' => 'gfyh9fe4',
            'email' => 'test@buckaroo.nl',
            'ratePlans' => [
                'add' => [
                    'startDate' => '2033-01-01',
                    'ratePlanCode' => '9863hdcj',
                ],
            ],
            'phone' => [
                'mobile' => '0612345678',
            ],
            'debtor' => [
                'code' => 'johnsmith4',
            ],
//            'person'                    => [
//                'firstName'         => 'John',
//                'lastName'          => 'Do',
//                'gender'            => Gender::FEMALE,
//                'culture'           => 'nl-NL',
//                'birthDate'         => '1990-01-01'
//            ],
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

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    public function it_updates_subscription()
    {
        $response = $this->buckaroo->method('subscriptions')->update([
            'subscriptionGuid' => 'FC512FC9CC3A485D8CF3D1804FF6xxxx',
            'configurationCode' => '9wqe32ew',
            'ratePlans' => [
                'update' => [
                    'ratePlanGuid' => 'F075470B1BB24B9291943A888A2Fxxxx',
                    'startDate' => '2022-01-01',
                    'endDate' => '2030-01-01',
                    'charge' => [
                        'ratePlanChargeGuid' => 'AD375E2E188747159673440898B9xxxx',
                        'baseNumberOfUnits' => '1',
                        'pricePerUnit' => 10,
                    ],
                ],
            ],
        ]);

        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_updates_combined_subscription()
    {
        $subscription = $this->buckaroo->method('subscriptions')->manually()->updateCombined([
            'startRecurrent' => true,
            'subscriptionGuid' => '65EB06079D854B0C9A9ECB0E2C1Cxxxx',
        ]);

        $response = $this->buckaroo->method('ideal')->combine($subscription)->pay([
            'invoice' => uniqid(),
            'amountDebit' => 10.10,
            'issuer' => 'ABNANL2A',
        ]);


        $this->assertTrue($response->isRejected());
    }

    /**
     * @return void
     * @test
     */
    public function it_stops_subscription()
    {
        $response = $this->buckaroo->method('subscriptions')->stop([
            'subscriptionGuid' => 'A8A3DF828F0E4706B50191D3D1C88xxx',
        ]);

        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_get_info_of_subscription()
    {
        $response = $this->buckaroo->method('subscriptions')->info([
            'subscriptionGuid' => '6ABDB214C4944B5C8638420CE9ECxxxx',
        ]);

        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_delete_payment_config_of_subscription()
    {
        $response = $this->buckaroo->method('subscriptions')->deletePaymentConfig([
            'subscriptionGuid' => '6ABDB214C4944B5C8638420CE9ECxxxx',
        ]);

        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_pauses_of_subscription()
    {
        $response = $this->buckaroo->method('subscriptions')->pause([
            'resumeDate' => '2030-01-01',
            'subscriptionGuid' => '6ABDB214C4944B5C8638420CE9ECxxxx',
        ]);

        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_resumes_of_subscription()
    {
        $response = $this->buckaroo->method('subscriptions')->resume([
            'resumeDate' => '2030-01-01',
            'subscriptionGuid' => '6ABDB214C4944B5C8638420CE9ECxxxx',
        ]);

        $this->assertTrue($response->isFailed());
    }
}
