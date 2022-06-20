<?php

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
        $response = $this->buckaroo->payment('buckaroo_wallet')->createWallet([
            'walletId'   => uniqid(),
            'email'         => 'test@buckaroo.nl',
            'customer'       => [
                'firstName'     => 'John',
                'lastName'      => 'Doe'
            ],
            'bankAccount'   => [
                'iban'      => 'NL13TEST0123456789',
            ]
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_updates_a_buckaroo_wallet()
    {
        $response = $this->buckaroo->payment('buckaroo_wallet')->updateWallet([
            'walletId'      => 10,
            'email'         => 'test@buckaroo.nl',
            'status'        => 'Disabled',
            'customer'       => [
                'firstName'     => 'John',
                'lastName'      => 'Doe'
            ],
            'bankAccount'   => [
                'iban'      => 'NL13TEST0123456789',
            ]
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_get_buckaroo_wallet_info()
    {
        $response = $this->buckaroo->payment('buckaroo_wallet')->getInfo([
            'walletId'      => 10
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_releases_buckaroo_wallet()
    {
        $response = $this->buckaroo->payment('buckaroo_wallet')->release([
            'amountCredit'          => 1,
            'walletId'              => 10,
            'walletMutationGuid'    => '1757B313E57E4973997DD8C5235A'
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_deposit_to_buckaroo_wallet()
    {
        $response = $this->buckaroo->payment('buckaroo_wallet')->deposit([
            'invoice'               => 'BuckarooWalletInvoiceId',
            'originalTransactionKey'    => '46FB241693914AA4AE7A8B6DB33DE',
            'amountCredit'          => 1,
            'walletId'              => 10
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_reserve_to_buckaroo_wallet()
    {
        $response = $this->buckaroo->payment('buckaroo_wallet')->reserve([
            'invoice'                   => 'BuckarooWalletInvoiceId',
            'originalTransactionKey'    => '46FB241693914AA4AE7A8B6DB33DE',
            'amountCredit'          => 1,
            'walletId'              => 10
        ]);

        $this->assertTrue($response->isValidationFailure());
    }
}