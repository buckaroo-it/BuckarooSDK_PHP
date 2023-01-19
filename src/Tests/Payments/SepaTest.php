<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class SepaTest extends BuckarooTestCase
{
    protected function setUp(): void
    {
        $this->paymentPayload = ([
            'invoice' => uniqid(),
            'amountDebit' => 10.10,
            'iban' => 'NL13TEST0123456789',
            'bic' => 'TESTNL2A',
            'collectdate' => '2022-12-01',
            'mandateReference' => '1DCtestreference',
            'mandateDate' => '2022-07-03',
            'customer' => [
                'name' => 'John Smith',
            ],
        ]);
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_sepa_payment()
    {
        $response = $this->buckaroo->method('sepadirectdebit')->pay($this->paymentPayload);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_sepa_refund()
    {
        $response = $this->buckaroo->method('sepadirectdebit')->refund([
            'amountCredit' => 10,
            'invoice' => 'testinvoice 123',
            'originalTransactionKey' => '3D175524FCF94C94A23B67E8DCXXXXXX',
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_sepa_authorize()
    {
        $response = $this->buckaroo->method('sepadirectdebit')->authorize($this->paymentPayload);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_sepa_recurrent_payment()
    {
        $response = $this->buckaroo->method('sepadirectdebit')->payRecurrent([
            'amountDebit' => 10,
            'originalTransactionKey' => 'FDA9EEEEA53C42BF875C35C6C2B7xxxx',
            'invoice' => 'testinvoice 123',
            'collectdate' => '2030-07-03',
        ]);

        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_sepa_extra_info()
    {
        $response = $this->buckaroo->method('sepadirectdebit')->extraInfo([
            'amountDebit' => 10,
            'invoice' => 'testinvoice 123',
            'iban' => 'NL13TEST0123456789',
            'bic' => 'TESTNL2A',
            'contractID' => 'TEST',
            'mandateDate' => '2022-07-03',
            'customerReferencePartyName' => 'Lorem',
            'customer' => [
                'name' => 'John Smith',
            ],
            'address' => [
                'street' => 'Hoofdstraat',
                'houseNumber' => '13',
                'houseNumberAdditional' => 'a',
                'zipcode' => '1234AB',
                'city' => 'Heerenveen',
                'country' => 'NL',
            ],
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_sepa_pay_with_emandate()
    {
        $response = $this->buckaroo->method('sepadirectdebit')->payWithEmandate([
            'amountDebit' => 10,
            'invoice' => 'testinvoice 123',
            'mandateReference' => '001D284C4A887F84756A1425A369997xxxx',
        ]);

        $this->assertTrue($response->isValidationFailure());
    }
}
