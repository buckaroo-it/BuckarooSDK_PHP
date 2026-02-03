<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\BuckarooWallet\Models;

use Buckaroo\PaymentMethods\BuckarooWallet\Models\Wallet;
use Buckaroo\PaymentMethods\BuckarooWallet\Service\ParameterKeys\CustomerAdapter;
use Buckaroo\PaymentMethods\BuckarooWallet\Service\ParameterKeys\EmailAdapter;
use Buckaroo\PaymentMethods\BuckarooWallet\Service\ParameterKeys\BankAccountAdapter;
use Tests\TestCase;

class WalletTest extends TestCase
{
    /** @test */
    public function it_sets_wallet_id(): void
    {
        $wallet = new Wallet(['walletId' => 'WALLET-123']);

        $this->assertSame('WALLET-123', $wallet->walletId);
    }

    /** @test */
    public function it_sets_status(): void
    {
        $wallet = new Wallet(['status' => 'active']);

        $this->assertSame('active', $wallet->status);
    }

    /** @test */
    public function it_sets_wallet_mutation_guid(): void
    {
        $wallet = new Wallet(['walletMutationGuid' => 'MUTATION-GUID-456']);

        $this->assertSame('MUTATION-GUID-456', $wallet->walletMutationGuid);
    }

    /** @test */
    public function it_sets_customer_from_array(): void
    {
        $wallet = new Wallet([
            'customer' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
        ]);

        $customer = $wallet->customer();

        $this->assertInstanceOf(CustomerAdapter::class, $customer);
    }

    /** @test */
    public function it_sets_email_from_string(): void
    {
        $wallet = new Wallet([
            'email' => 'test@example.com',
        ]);

        $email = $wallet->email();

        $this->assertInstanceOf(EmailAdapter::class, $email);
    }

    /** @test */
    public function it_sets_bank_account_from_array(): void
    {
        $wallet = new Wallet([
            'bankAccount' => [
                'iban' => 'NL91ABNA0417164300',
            ],
        ]);

        $bankAccount = $wallet->bankAccount();

        $this->assertInstanceOf(BankAccountAdapter::class, $bankAccount);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $wallet = new Wallet([
            'walletId' => 'WALLET-789',
            'status' => 'active',
            'walletMutationGuid' => 'MUTATION-GUID-789',
            'customer' => ['firstName' => 'Jane', 'lastName' => 'Doe'],
            'email' => 'jane@example.com',
            'bankAccount' => ['iban' => 'NL91ABNA0417164300'],
        ]);

        $this->assertSame('WALLET-789', $wallet->walletId);
        $this->assertSame('active', $wallet->status);
        $this->assertSame('MUTATION-GUID-789', $wallet->walletMutationGuid);
        $this->assertInstanceOf(CustomerAdapter::class, $wallet->customer());
        $this->assertInstanceOf(EmailAdapter::class, $wallet->email());
        $this->assertInstanceOf(BankAccountAdapter::class, $wallet->bankAccount());
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $wallet = new Wallet([]);

        $array = $wallet->toArray();
        $this->assertIsArray($array);
    }
}
