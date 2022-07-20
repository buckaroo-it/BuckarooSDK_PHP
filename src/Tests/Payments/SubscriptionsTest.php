<?php

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
        $response = $this->buckaroo->payment('subscriptions')->create([
            'rate_plans'        => [
                'add'        => [
                    'startDate'         => carbon()->format('Y-m-d'),
                    'ratePlanCode'      => 'xxxxxx',
                ]
            ],
            'configurationCode' => 'xxxxx',
            'debtor'            => [
                'code'          => 'xxxxxx'
            ]
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_combined_subscription()
    {
        $subscriptions = $this->buckaroo->payment('subscriptions')->manually()->createCombined([
            'includeTransaction'        => false,
            'transactionVatPercentage'  => 5,
            'configurationCode'         => 'xxxxx',
            'email'                     => 'test@buckaroo.nl',
            'rate_plans'        => [
                'add'        => [
                    'startDate'         => carbon()->format('Y-m-d'),
                    'ratePlanCode'      => 'xxxxxx',
                ]
            ],
            'phone'                     => [
                'mobile'                => '0612345678'
            ],
            'debtor'                    => [
                'code'          => 'xxxxxx'
            ],
            'person'                    => [
                'firstName'         => 'John',
                'lastName'          => 'Do',
                'gender'            => Gender::FEMALE,
                'culture'           => 'nl-NL',
                'birthDate'         => carbon()->subYears(24)->format('Y-m-d')
            ],
            'address'           => [
                'street'        => 'Hoofdstraat',
                'houseNumber'   => '90',
                'zipcode'       => '8441ER',
                'city'          => 'Heerenveen',
                'country'       => 'NL'
            ]
        ]);

        $response = $this->buckaroo->payment('ideal')->combine($subscriptions)->pay([
            'invoice'       => uniqid(),
            'amountDebit' => 10.10,
            'issuer' => 'ABNANL2A'
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_updates_subscription()
    {
        $response = $this->buckaroo->payment('subscriptions')->update([
            'subscriptionGuid'  => 'FC512FC9CC3A485D8CF3D1804FF6xxxx',
            'configurationCode' => '9wqe32ew',
            'rate_plans'        => [
                'update'        => [
                    'ratePlanGuid'  => 'F075470B1BB24B9291943A888A2Fxxxx',
                    'startDate' => carbon()->format('Y-m-d'),
                    'endDate'   => carbon()->addDays(30)->format('Y-m-d'),
                    'charge'        => [
                        'ratePlanChargeGuid'              => 'AD375E2E188747159673440898B9xxxx',
                        'baseNumberOfUnits' => '1',
                        'pricePerUnit'      => 10
                    ]
                ]
            ]
        ]);

        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_updates_combined_subscription()
    {
        $subscription = $this->buckaroo->payment('subscriptions')->manually()->updateCombined([
            'startRecurrent'            => true,
            'subscriptionGuid'        => '65EB06079D854B0C9A9ECB0E2C1Cxxxx'
        ]);

        $response = $this->buckaroo->payment('ideal')->combine($subscription)->pay([
            'invoice'       => uniqid(),
            'amountDebit' => 10.10,
            'issuer' => 'ABNANL2A'
        ]);


        $this->assertTrue($response->isRejected());
    }

    /**
     * @return void
     * @test
     */
    public function it_stops_subscription()
    {
        $response = $this->buckaroo->payment('subscriptions')->stop([
            'subscriptionGuid'        => 'A8A3DF828F0E4706B50191D3D1C88xxx'
        ]);

        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_get_info_of_subscription()
    {
        $response = $this->buckaroo->payment('subscriptions')->info([
            'subscriptionGuid'        => '6ABDB214C4944B5C8638420CE9ECxxxx'
        ]);

        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_delete_payment_config_of_subscription()
    {
        $response = $this->buckaroo->payment('subscriptions')->deletePaymentConfig([
            'subscriptionGuid'        => '6ABDB214C4944B5C8638420CE9ECxxxx'
        ]);

        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_pauses_of_subscription()
    {
        $response = $this->buckaroo->payment('subscriptions')->pause([
            'resumeDate'                => carbon()->addDays(10)->format('Y-m-d'),
            'subscriptionGuid'        => '6ABDB214C4944B5C8638420CE9ECxxxx'
        ]);

        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_resumes_of_subscription()
    {
        $response = $this->buckaroo->payment('subscriptions')->resume([
            'resumeDate'                => carbon()->addDays(10)->format('Y-m-d'),
            'subscriptionGuid'        => '6ABDB214C4944B5C8638420CE9ECxxxx'
        ]);

        $this->assertTrue($response->isFailed());
    }
}
