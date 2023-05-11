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

class PayPerEmailTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_invites_pay_per_email()
    {
        $response = $this->buckaroo->method('payperemail')->paymentInvitation([
            'amountDebit' => 10,
            'invoice' => 'testinvoice 123',
            'merchantSendsEmail' => false,
            'email' => 'johnsmith@gmail.com',
            'expirationDate' => '2030-01-01',
            'paymentMethodsAllowed' => 'ideal,mastercard,paypal',
            'attachment' => '',
            'customer' => [
                'gender' => Gender::FEMALE,
                'firstName' => 'John',
                'lastName' => 'Smith',
            ],
        ]);

        $this->assertTrue($response->isAwaitingConsumer());
    }

    /**
     * @return void
     * @test
     */
    public function it_invites_pay_per_email_with_attachments()
    {
        $response = $this->buckaroo->method('payperemail')->paymentInvitation([
            'amountDebit' => 10,
            'invoice' => 'testinvoice 123',
            'merchantSendsEmail' => false,
            'email' => 'johnsmith@gmail.com',
            'expirationDate' => '2030-01-01',
            'paymentMethodsAllowed' => 'ideal,mastercard,paypal',
            'attachment' => '',
            'customer' => [
                'gender' => Gender::FEMALE,
                'firstName' => 'John',
                'lastName' => 'Smith',
            ],
            'attachments' => [
                ['name' => 'bijlage1.pdf'],
                ['name' => 'bijlage2.pdf'],
            ],
        ]);

        $this->assertTrue($response->isFailed());
    }
}
