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

class BuckarooWalletTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_buckaroo_wallet()
    {
        $response = $this->buckaroo->method('buckaroo_wallet')->createWallet([
            'walletId' => uniqid(),
            'email' => 'test@buckaroo.nl',
            'customer' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
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
    public function it_updates_a_buckaroo_wallet()
    {
        $response = $this->buckaroo->method('buckaroo_wallet')->updateWallet([
            'walletId' => 10,
            'email' => 'test@buckaroo.nl',
            'status' => 'Disabled',
            'customer' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
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
    public function it_get_buckaroo_wallet_info()
    {
        $response = $this->buckaroo->method('buckaroo_wallet')->getInfo([
            'walletId' => 10,
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_releases_buckaroo_wallet()
    {
        $response = $this->buckaroo->method('buckaroo_wallet')->release([
            'amountCredit' => 1,
            'walletId' => 10,
            'walletMutationGuid' => '1757B313E57E4973997DD8C5235A',
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_deposit_to_buckaroo_wallet()
    {
        $response = $this->buckaroo->method('buckaroo_wallet')->deposit([
            'invoice' => 'BuckarooWalletInvoiceId',
            'originalTransactionKey' => '46FB241693914AA4AE7A8B6DB33DE',
            'amountCredit' => 1,
            'walletId' => 10,
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_reserve_to_buckaroo_wallet()
    {
        $response = $this->buckaroo->method('buckaroo_wallet')->reserve([
            'invoice' => 'BuckarooWalletInvoiceId',
            'originalTransactionKey' => '46FB241693914AA4AE7A8B6DB33DE',
            'amountCredit' => 1,
            'walletId' => 10,
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_withdrawal_from_buckaroo_wallet()
    {
        $response = $this->buckaroo->method('buckaroo_wallet')->withdrawal([
            'invoice' => 'BuckarooWalletInvoiceId',
            'originalTransactionKey' => '46FB241693914AA4AE7A8B6DB33DE',
            'amountDebit' => 1,
            'walletId' => 10,
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_cancel_from_buckaroo_wallet()
    {
        $response = $this->buckaroo->method('buckaroo_wallet')->cancel([
            'invoice' => 'BuckarooWalletInvoiceId',
            'originalTransactionKey' => '46FB241693914AA4AE7A8B6DB33DE',
            'amountDebit' => 1,
            'walletMutationGuid' => '49B018248ECE4346AC20B902',
        ]);

        $this->assertTrue($response->isValidationFailure());
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
            'walletId' => 10,
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_refund_on_buckaroo_wallet()
    {
        $response = $this->buckaroo->method('buckaroo_wallet')->refund([
            'amountCredit' => 10,
            'invoice' => 'testinvoice 123',
            'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX',
        ]);

        $this->assertTrue($response->isFailed());
    }
}
