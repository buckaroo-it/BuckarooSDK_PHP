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

class PaypalTest extends BuckarooTestCase
{
    /**
     * @test
     */
    public function it_creates_a_paypal_payment()
    {
        $response = $this->buckaroo->method('paypal')->pay([
            'amountDebit' => 10,
            'invoice' => uniqid(),
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_paypal_recurrent_payment()
    {
        $response = $this->buckaroo->method('paypal')->payRecurrent([
            'amountDebit' => 10,
            'originalTransactionKey' => 'C32C0B52E1FE4A37835FFB1716XXXXXX',
            'invoice' => uniqid(),
        ]);

        $this->assertTrue($response->isFailed());
    }

    /**
     * @test
     */
    public function it_creates_a_paypal_extra_info()
    {
        $response = $this->buckaroo->method('paypal')->extraInfo([
            'amountDebit' => 10,
            'invoice' => uniqid(),
            'customer' => [
                'name' => 'John Smith',
            ],
            'address' => [
                'street' => 'Hoofstraat 90',
                'street2' => 'Street 2',
                'city' => 'Heerenveen',
                'state' => 'Friesland',
                'zipcode' => '8441AB',
                'country' => 'NL',
            ],
            'phone' => [
                'mobile' => '0612345678',
            ],
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_paypal_refund()
    {
        $response = $this->buckaroo->method('paypal')->refund([
            'amountCredit' => 10,
            'invoice' => 'testinvoice 123',
            'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX',
        ]);

        $this->assertTrue($response->isFailed());
    }

    /**
     * @test
     */
    public function it_creates_a_combined_subscriptions_with_paypal_and_extra_info()
    {
        $subscriptions = $this->buckaroo->method('subscriptions')->manually()->createCombined([
            'includeTransaction' => false,
            'transactionVatPercentage' => 5,
            'configurationCode' => 'xxxxx',
            'email' => 'test@buckaroo.nl',
            'rate_plans' => [
                'add' => [
                    'startDate' => '2022-01-01',
                    'ratePlanCode' => 'xxxxxx',
                ],
            ],
            'phone' => [
                'mobile' => '0612345678',
            ],
            'debtor' => [
                'code' => 'xxxxxx',
            ],
            'person' => [
                'firstName' => 'John',
                'lastName' => 'Do',
                'gender' => Gender::FEMALE,
                'culture' => 'nl-NL',
                'birthDate' => '1990-01-01',
            ],
            'address' => [
                'street' => 'Hoofdstraat',
                'houseNumber' => '90',
                'zipcode' => '8441ER',
                'city' => 'Heerenveen',
                'country' => 'NL',
            ],
        ]);

        $paypal_extra_info = $this->buckaroo->method('paypal')->manually()->extraInfo([
            'amountDebit' => 10,
            'invoice' => uniqid(),
            'customer' => [
                'name' => 'John Smith',
            ],
            'address' => [
                'street' => 'Hoofstraat 90',
                'street2' => 'Street 2',
                'city' => 'Heerenveen',
                'state' => 'Friesland',
                'zipcode' => '8441AB',
                'country' => 'NL',
            ],
            'phone' => [
                'mobile' => '0612345678',
            ],
        ]);

        $response = $this->buckaroo->method('paypal')->combine([$subscriptions, $paypal_extra_info])->pay([
            'amountDebit' => 10,
            'invoice' => uniqid(),
        ]);

        $this->assertTrue($response->isValidationFailure());
    }
}
