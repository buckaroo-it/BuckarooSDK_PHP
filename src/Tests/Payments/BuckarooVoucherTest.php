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

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class BuckarooVoucherTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_buckaroo_voucher()
    {
        $response = $this->buckaroo->method('buckaroovoucher')->create(
            [
                'usageType' => '2',
                'validFrom' => '2022-01-01',
                'validUntil' => '2024-01-01',
                'creationBalance' => '5',
            ]
        );

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_buckaroo_payment()
    {
        $response = $this->buckaroo->method('buckaroovoucher')->pay(
            [
                'amountDebit' => '10',
                'invoice' => uniqid(),
                'vouchercode' => 'vouchercode',
            ]
        );

        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_buckaroo_pay_remainder()
    {
        $response = $this->buckaroo->method('buckaroovoucher')->payRemainder(
            [
                'amountDebit' => '10',
                'invoice' => uniqid(),
                'vouchercode' => 'vouchercode',
                'originalTransaction' => '4E8BD922192746C3918BF4077CXXXXXX',
            ]
        );

        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_buckaroo_refund()
    {
        $response = $this->buckaroo->method('buckaroovoucher')->refund(
            [
                'amountCredit' => 10,
                'invoice' => uniqid(),
                'originalTransactionKey' => '4E8BD922192746C3918BF4077CXXXXXX',
            ]
        );

        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_buckaroo_get_balance()
    {
        $response = $this->buckaroo->method('buckaroovoucher')->getBalance(
            [
                'vouchercode' => 'vouchercode',
            ]
        );

        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_deactivate_a_buckaroo_voucher()
    {
        $response = $this->buckaroo->method('buckaroovoucher')->deactivate(
            [
                'vouchercode' => 'vouchercode',
            ]
        );
        
        $this->assertTrue($response->isFailed());
    }
}
