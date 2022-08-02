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
        $response = $this->buckaroo->method('giftcard')->pay([
            'amountDebit'           => 10,
            'invoice'               => uniqid(),
            'name'                  => 'boekenbon',
            'intersolveCardnumber'  => '0000000000000000001',
            'intersolvePIN'         => '1000'
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @test
     */
    public function it_creates_a_giftcards_partial_payment()
    {
        $giftCardResponse = $this->buckaroo->method('giftcard')->pay([
            'amountDebit'           => 10,
            'invoice'               => uniqid(),
            'name'                  => 'boekenbon',
            'intersolveCardnumber'  => '0000000000000000001',
            'intersolvePIN'         => '500'
        ]);

        $this->assertTrue($giftCardResponse->isSuccess());

        $response = $this->buckaroo->method('ideal')->payRemainder([
            'originalTransactionKey'    => $giftCardResponse->data('RelatedTransactions')[0]['RelatedTransactionKey'],
            'invoice'                   => $giftCardResponse->data('Invoice'),
            'amountDebit'               => 10.10,
            'issuer'                    => 'ABNANL2A'
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_giftcards_refund()
    {
        $response = $this->buckaroo->method('giftcard')->refund([
            'amountCredit'              => 10,
            'invoice'                   => 'testinvoice 123',
            'originalTransactionKey'    => '2D04704995B74D679AACC59F87XXXXXX',
            'name'                      => 'boekenbon'
        ]);

        $this->assertTrue($response->isFailed());
    }
}