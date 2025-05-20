<?php

namespace Tests\Buckaroo\Payments;

use Tests\Buckaroo\BuckarooTestCase;

class SepaTest extends BuckarooTestCase
{

    /**
     * @return void
     * @test
     */
    public function it_creates_a_sepa_payment()
    {
        $response = $this->buckaroo->method('sepadirectdebit')->pay($this->getBasePayPayload([], [
            'iban' => 'NL13TEST0123456789',
            'bic' => 'TESTNL2A',
            'collectdate' => '2022-12-01',
            'mandateReference' => '1DCtestreference',
            'mandateDate' => '2022-07-03',
            'customer' => [
                'name' => 'John Smith',
            ],
        ]));

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_sepa_refund()
    {
        $response = $this->buckaroo->method('sepadirectdebit')->refund($this->getRefundPayload([
            'originalTransactionKey' => '5221F6CECF4E4C7791BB57BC78C0CF7A',
        ]));

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_sepa_authorize()
    {
        $response = $this->buckaroo->method('sepadirectdebit')->authorize([
            'amountDebit' => 10,
            'invoice' => uniqid(),
            'iban' => 'NL13TEST0123456789',
            'bic' => 'TESTNL2A',
            'collectdate' => '2025-12-01',
            'mandateReference' => '1DC326734AB3084FC7',
            'mandateDate' => '2025-07-03',
            'startRecurrent' => true,
            'channel' => 'BackOffice',
            'customer' => [
                'name' => 'John Smith',
            ],
        ]);

        $this->assertTrue($response->isSuccess());
    }

    //Todo: Failing
    /**
     * @return void
     * @test
     */
    public function it_creates_a_sepa_recurrent_payment()
    {
        $response = $this->buckaroo->method('sepadirectdebit')->payRecurrent($this->getBasePayPayload([], [
            'originalTransactionKey' => '9D2855A4ED164EE7954E71E3154873DE',
            'collectdate' => '2030-07-03',
            'order' => '',
        ]));

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
        $response = $this->buckaroo->method('sepadirectdebit')->payWithEmandate($this->getBasePayPayload([], [
            'mandateReference' => '1DC326734AB3084FC7',
            'order' => '',
        ]));

        $this->assertTrue($response->isPendingProcessing());
    }
}
