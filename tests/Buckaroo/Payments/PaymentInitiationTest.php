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
        $response = $this->buckaroo->method('paybybank')->pay($this->getBasePayPayload([], [
            'issuer' => 'ABNANL2A',
            'countryCode' => 'NL',
            'pushURL' => 'https://example.com/buckaroo/push',
        ]));

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_payment_initiation_refund()
    {
        $response = $this->buckaroo->method('paybybank')->refund($this->getRefundPayload([
            'originalTransactionKey' => 'F7B4C318221D41F185728116F05C9EF7',
            'invoice' => '670fa9e86d347',
            'pushURL' => 'https://example.com/buckaroo/push',
        ]));

        $this->assertTrue($response->isSuccess());
    }
}
