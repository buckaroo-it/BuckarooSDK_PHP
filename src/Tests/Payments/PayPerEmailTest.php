<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Resources\Constants\Gender;
use Buckaroo\Tests\BuckarooTestCase;

class PayPerEmailTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_invites_pay_per_email()
    {
        $response = $this->buckaroo->method('payperemail')->paymentInvitation([
            'amountDebit'           => 10,
            'invoice'               => 'testinvoice 123',
            'merchantSendsEmail'    => false,
            'email'                 => 'johnsmith@gmail.com',
            'expirationDate'        => carbon()->addDays()->format('Y-m-d'),
            'paymentMethodsAllowed' => 'ideal,mastercard,paypal',
            'attachment'            => '',
            'customer'              => [
                'gender'        => Gender::FEMALE,
                'firstName'     => 'John',
                'lastName'      => 'Smith'
            ]
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
            'amountDebit'           => 10,
            'invoice'               => 'testinvoice 123',
            'merchantSendsEmail'    => false,
            'email'                 => 'johnsmith@gmail.com',
            'expirationDate'        => carbon()->addDays()->format('Y-m-d'),
            'paymentMethodsAllowed' => 'ideal,mastercard,paypal',
            'attachment'            => '',
            'customer'              => [
                'gender'        => Gender::FEMALE,
                'firstName'     => 'John',
                'lastName'      => 'Smith'
            ],
            'attachments'       => [
                ['name'     => 'bijlage1.pdf'],
                ['name'     => 'bijlage2.pdf']
            ]
        ]);

        $this->assertTrue($response->isFailed());

    }
}