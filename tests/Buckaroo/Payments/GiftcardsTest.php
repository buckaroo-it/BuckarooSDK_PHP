<?php
/*
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * It is available through the world-wide-web at this URL:
 * https://tldrlegal.com/license/mit-license
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to support@buckaroo.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact support@buckaroo.nl for more information.
 *
 * @copyright Copyright (c) Buckaroo B.V.
 * @license   https://tldrlegal.com/license/mit-license
 */

namespace Tests\Buckaroo\Payments;

use Tests\Buckaroo\BuckarooTestCase;

class GiftcardsTest extends BuckarooTestCase
{
    /**
     * @test
     */
    public function it_creates_a_giftcards_payment()
    {
        $response = $this->buckaroo->method('giftcard')->pay([
            'amountDebit' => 10,
            'invoice' => uniqid(),
            'name' => 'boekenbon',
            'intersolveCardnumber' => '0000000000000000001',
            'intersolvePIN' => '1000',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @test
     */
    public function it_creates_a_giftcards_partial_payment()
    {
        $giftCardResponse = $this->buckaroo->method('giftcard')->pay([
            'amountDebit' => 10,
            'invoice' => uniqid(),
            'name' => 'boekenbon',
            'intersolveCardnumber' => '0000000000000000001',
            'intersolvePIN' => '500',
        ]);

        $this->assertTrue($giftCardResponse->isSuccess());

        $response = $this->buckaroo->method('ideal')->payRemainder([
            'originalTransactionKey' => $giftCardResponse->data('RelatedTransactions')[0]['RelatedTransactionKey'],
            'invoice' => $giftCardResponse->data('Invoice'),
            'amountDebit' => 10.10,
            'issuer' => 'ABNANL2A',
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_giftcards_refund()
    {
        $response = $this->buckaroo->method('giftcard')->refund([
            'amountCredit' => 10,
            'invoice' => 'testinvoice 123',
            'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX',
            'name' => 'boekenbon',
            'email' => 'test123@hotmail.com',
            'lastname' => 'test123'
        ]);

        $this->assertTrue($response->isFailed());
    }
}
