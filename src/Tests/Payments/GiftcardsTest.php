<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class GiftcardsTest extends BuckarooTestCase
{
    /**
     * @test
     */
    public function it_creates_a_giftcards_payment()
    {
        $response = $this->buckaroo->payment('giftcard')->pay([
            'amountDebit' => 10,
            'invoice' => uniqid(),
            'serviceParameters' => [
                'name'          => 'boekenbon',
                'voucher'      => [
                    'intersolveCardnumber' => '0000000000000000001',
                    'intersolvePin'        => '1000'
                ]
            ]
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @test
     */
    public function it_creates_a_giftcards_refund()
    {
        $response = $this->buckaroo->payment('giftcard')->refund([
            'amountCredit' => 10,
            'invoice'       => 'testinvoice 123',
            'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX',
            'serviceParameters' => [
                'name'          => 'boekenbon'
            ]
        ]);

        $this->assertTrue($response->isFailed());
    }
}