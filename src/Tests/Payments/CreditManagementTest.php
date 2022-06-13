<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class CreditManagementTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_invoice()
    {
        $response = $this->buckaroo->payment('credit_management')->createInvoice([
            'amountDebit' => 10,
            'invoice' => uniqid(),
            'serviceParameters' => [
                'customer'      => [
                    'firstName' => 'Test',
                    'lastName' => 'Aflever',
                    'email' => 'billingcustomer@buckaroo.nl'
                ]
            ]
        ]);

        dd($response);
        $this->assertTrue($response->isPendingProcessing());
    }
}