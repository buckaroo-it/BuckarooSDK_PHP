<?php

namespace Tests\Buckaroo\Payments;

use Tests\Buckaroo\BuckarooTestCase;

/**
 *
 */
class BatchTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_tests_batch_api()
    {
        $transactions = array();

        for ($i = 0; $i < 3; $i++) {
            $invoice = $this->buckaroo->method('credit_management')->manually()->createCombinedInvoice($this->getInvoicePayload(['invoice' => uniqid()]));

            $transactions[]  = $this->buckaroo->method('sepadirectdebit')->combine($invoice)->manually()->pay($this->getBasePayPayload([],[
                'iban' => 'NL13TEST0123456789',
                'bic' => 'TESTNL2A',
                'collectdate' => date('Y-m-d'),
                'mandateReference' => '1DCtestreference',
                'mandateDate' => '2022-07-03',
                'customer' => [
                    'name' => 'John Smith',
                ],
            ]));
        }

        $response = $this->buckaroo->batch($transactions)->execute();

        $this->assertTrue($response->data('Message') == '3 data requests were queued for processing.');
    }
}
