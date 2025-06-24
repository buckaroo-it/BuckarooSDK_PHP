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

class BuckarooWalletTest extends BuckarooTestCase
{
    private static ?string $walletId = null;
    private static ?string $reservationId = null;

    /**
     * @return void
     * @test
     */
    public function it_creates_a_buckaroo_wallet()
    {
        $response = $this->buckaroo->method('buckaroo_wallet')->createWallet([
            'walletId' => uniqid(),
            'currency' => 'EUR',
            'customer' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
                'email' => 'test@buckaroo.nl',
            ],
            'bankAccount' => [
                'iban' => 'NL13TEST0123456789',
            ]
        ]);

        $this->assertTrue($response->isSuccess());

        self::$walletId = $response->getServiceParameters()['walletid'];
    }

    /**
     * @return void
     * @test
     */
    public function it_updates_a_buckaroo_wallet()
    {
        $response = $this->buckaroo->method('buckaroo_wallet')->updateWallet([
            'walletId' => self::$walletId,
            'status' => 'Active',
            'customer' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
                'email' => 'test@buckaroo.nl',
            ],
            'bankAccount' => [
                'iban' => 'NL13TEST0123456789',
            ],
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_gets_buckaroo_wallet_info()
    {
        $response = $this->buckaroo->method('buckaroo_wallet')->getInfo([
            'walletId' => self::$walletId,
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_deposit_to_buckaroo_wallet()
    {
        $response = $this->buckaroo->method('buckaroo_wallet')->deposit([
            'invoice' => 'BuckarooWalletInvoiceId',
            'originalTransactionKey' => '46CA38B421194B6FAE5AFD42619715FD',
            'amountCredit' => 10,
            'walletId' => self::$walletId,
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_reserve_to_buckaroo_wallet()
    {
        $response = $this->buckaroo->method('buckaroo_wallet')->reserve([
            'invoice' => 'BuckarooWalletInvoiceId',
            'originalTransactionKey' => '46CA38B421194B6FAE5AFD42619715FD',
            'amountCredit' => 10,
            'walletId' => self::$walletId,
        ]);

        self::$reservationId = $response->getTransactionKey();

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_releases_buckaroo_wallet()
    {
        $response = $this->buckaroo->method('buckaroo_wallet')->release([
            'amountCredit' => 10,
            'walletId' => self::$walletId,
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_withdrawal_from_buckaroo_wallet()
    {
        $response = $this->buckaroo->method('buckaroo_wallet')->withdrawal([
            'invoice' => 'BuckarooWalletInvoiceId',
            'originalTransactionKey' => self::$reservationId,
            'amountDebit' => 1,
            'walletId' => self::$walletId,
            'order' => ''
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_payment_on_buckaroo_wallet()
    {
        $response = $this->buckaroo->method('buckaroo_wallet')->pay([
            'invoice' => 'BuckarooWalletInvoiceId',
            'description' => 'Test',
            'amountDebit' => 1,
            'walletId' => self::$walletId,
        ]);

        $this->assertTrue($response->isSuccess());

        self::$payTransactionKey = $response->getTransactionKey();
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_refund_on_buckaroo_wallet()
    {
        $response = $this->buckaroo->method('buckaroo_wallet')->refund([
            'amountCredit' => 1,
            'invoice' => 'testinvoice 123',
            'originalTransactionKey' => self::$payTransactionKey,
        ]);

        $this->assertTrue($response->isSuccess());
    }
}
