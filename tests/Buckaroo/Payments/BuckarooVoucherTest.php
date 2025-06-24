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

class BuckarooVoucherTest extends BuckarooTestCase
{
    private static ?string $voucherCode = null;
    /**
     * @return void
     * @test
     */
    public function it_creates_a_buckaroo_voucher()
    {
        $response = $this->buckaroo->method('buckaroovoucher')->create(
            [
                'usageType' => '2',
                'validFrom' => '2025-01-01',
                'validUntil' => '2030-01-01',
                'creationBalance' => '10',
            ]
        );

        $this->assertTrue($response->isSuccess());

        self::$voucherCode = $response->getServiceParameters()['vouchercode'];
    }

    /**
     * @return void
     * @test
     * @depends it_creates_a_buckaroo_voucher
     */
    public function it_creates_a_buckaroo_payment()
    {
        $response = $this->buckaroo->method('buckaroovoucher')->pay($this->getPayPayload([
            'vouchercode' => self::$voucherCode,
            'amountDebit' => 5,
        ]));

        $this->assertTrue($response->isSuccess());

        self::$payTransactionKey = $response->getTransactionKey();
    }

    /**
     * @return void
     * @test
     * @depends it_creates_a_buckaroo_voucher
     */
    public function it_creates_a_buckaroo_pay_remainder()
    {
        $giftCardResponse = $this->buckaroo->method('giftcard')->pay($this->getBasePayPayload([], [
            'amountDebit' => 10,
            'name' => 'boekenbon',
            'intersolveCardnumber' => '0000000000000000001',
            'intersolvePIN' => '500',
        ]));

        $this->assertTrue($giftCardResponse->isSuccess());

        $response = $this->buckaroo->method('buckaroovoucher')->payRemainder($this->getBasePayPayload([], [
            'originalTransactionKey' => $giftCardResponse->data('RelatedTransactions')[0]['RelatedTransactionKey'],
            'amountDebit' => 9.50,
            'invoice' => uniqid(),
            'vouchercode' => self::$voucherCode,
        ]));

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     * @depends it_creates_a_buckaroo_payment
     */
    public function it_creates_a_buckaroo_refund()
    {
        $response = $this->buckaroo->method('buckaroovoucher')->refund(
            [
                'amountCredit' => 5,
                'invoice' => uniqid(),
                'originalTransactionKey' => self::$payTransactionKey,
            ]
        );

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     * @depends it_creates_a_buckaroo_voucher
     */
    public function it_creates_a_buckaroo_get_balance()
    {
        $response = $this->buckaroo->method('buckaroovoucher')->getBalance(
            [
                'vouchercode' => self::$voucherCode,
            ]
        );

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     * @depends it_creates_a_buckaroo_voucher
     */
    public function it_deactivate_a_buckaroo_voucher()
    {
        $response = $this->buckaroo->method('buckaroovoucher')->deactivate(
            [
                'vouchercode' => self::$voucherCode,
            ]
        );

        $this->assertTrue($response->isSuccess());
    }
}
