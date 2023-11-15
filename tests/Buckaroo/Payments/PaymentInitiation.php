<?php

namespace Tests\Buckaroo\Payments;

use Tests\Buckaroo\BuckarooTestCase;

class PaymentInitiationTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_get_payment_initiation_issuers()
    {
        $response = $this->buckaroo->method('paybybank')->issuers();

        $this->assertIsArray($response);
        foreach ($response as $item)
        {
            $this->assertIsArray($item);
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('name', $item);
        }
    }

    /**
     * @test
     */
    public function it_creates_a_payment_initiation_payment()
    {
        $response = $this->buckaroo->method('paybybank')->pay([
            'amountDebit' => 10,
            'description' => 'Payment for testinvoice123',
            'invoice' => uniqid(),
            'issuer' => 'ABNANL2A',
            'countryCode' => 'NL'
        ]);

        $this->assertTrue($response->isFailed());
    }

    /**
     * @test
     */
    public function it_creates_a_payment_initiation_refund()
    {
        $response = $this->buckaroo->method('paybybank')->refund([
            'amountCredit' => 10,
            'invoice' => 'testinvoice 123',
            'originalTransactionKey' => '4E8BD922192746C3918BF4077CXXXXXX',
        ]);

        $this->assertTrue($response->isFailed());
    }
}
