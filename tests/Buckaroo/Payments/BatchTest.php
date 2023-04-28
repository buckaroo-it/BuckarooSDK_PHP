<?php

namespace Tests\Buckaroo\Payments;

use Tests\Buckaroo\BuckarooTestCase;

class BatchTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_tests_batch_api()
    {
        $transactions = array();

        for($i=0;$i<10;$i++)
        {
            $transactions[] = $this->buckaroo->method('ideal')->manually()->pay([
                'invoice' => uniqid(),
                'amountDebit' => 10.10,
                'issuer' => 'ABNANL2A',
                'pushURL' => 'https://buckaroo.dev/push',
                'returnURL' => 'https://buckaroo.dev/return',
                'clientIP' => [
                    'address' => '123.456.789.123',
                    'type' => 0,
                ],
                'additionalParameters' => [
                    'initiated_by_magento' => 1,
                    'service_action' => 'something',
                ],
            ])->request();
        }

        dd($transactions);
        $this->assertTrue(true);
        $ideal = $this->buckaroo->method('ideal')->manually()->pay([
            'invoice' => uniqid(),
            'amountDebit' => 10.10,
            'issuer' => 'ABNANL2A',
            'pushURL' => 'https://buckaroo.dev/push',
            'returnURL' => 'https://buckaroo.dev/return',
            'clientIP' => [
                'address' => '123.456.789.123',
                'type' => 0,
            ],
            'additionalParameters' => [
                'initiated_by_magento' => 1,
                'service_action' => 'something',
            ],
        ]);
        dd($ideal);
        $response = $this->buckaroo->batch('ransactions');
    }
}