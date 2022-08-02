<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class CreditClickTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditclick_payment()
    {
        $response = $this->buckaroo->method('creditclick')->pay([
            'amountDebit' => 10,
            'invoice' => uniqid(),
            'customer'      => [
                'firstName' => 'Test',
                'lastName' => 'Aflever',
            ],
            'email'         => 't.tester@test.nl'
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_creditclick_refund()
    {
        $response = $this->buckaroo->method('creditclick')->refund([
            'amountCredit'              => 10,
            'invoice'                   => 'testinvoice 123',
            'description'               => 'refund',
            'originalTransactionKey'    => '2D04704995B74D679AACC59F87XXXXXX',
            'refundreason'              => 'RequestedByCustomer'
        ]);

        $this->assertTrue($response->isFailed());
    }
}