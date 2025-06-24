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
        $response = $this->buckaroo->method('giftcard')->pay($this->getBasePayPayload([], [
            'amountDebit' => 40.30,
            'name' => 'boekenbon',
            'intersolveCardnumber' => '0000000000000000001',
            'intersolvePIN' => '4030',
            'email'         => 'test@buckar00.nl',
            'lastName'         => 'Test'
        ]));

        $this->assertTrue($response->isSuccess());

        self::$payTransactionKey = $response->getTransactionKey();
    }

    /**
     * @test
     */
    public function it_creates_a_giftcards_partial_payment()
    {
        $giftCardResponse = $this->buckaroo->method('giftcard')->pay($this->getBasePayPayload([], [
            'amountDebit' => 10,
            'name' => 'boekenbon',
            'intersolveCardnumber' => '0000000000000000001',
            'intersolvePIN' => '0500',
            'email'         => 'test@buckar00.nl',
            'lastName'         => 'Test'

        ]));

        $this->assertTrue($giftCardResponse->isSuccess());

        $response = $this->buckaroo->method('giftcard')->payRemainder($this->getBasePayPayload([], [
            'originalTransactionKey' => $giftCardResponse->data('RelatedTransactions')[0]['RelatedTransactionKey'],
            'amountDebit' => 5.00,
            'name' => 'boekenbon',
            'intersolveCardnumber' => '0000000000000000001',
            'intersolvePIN' => '0500',
            'email'         => 'test@buckar00.nl',
            'lastName'         => 'Test'
        ]));

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @test
     */
    public function it_creates_a_giftcards_refund()
    {
        $response = $this->buckaroo->method('giftcard')->refund(
            $this->getRefundPayload([
                'originalTransactionKey' => self::$payTransactionKey,
                'name' => 'boekenbon',
                'email'         => 'test@buckar00.nl',
                'lastName'         => 'Test'
            ])
        );

        $this->assertTrue($response->isSuccess());
    }
}
