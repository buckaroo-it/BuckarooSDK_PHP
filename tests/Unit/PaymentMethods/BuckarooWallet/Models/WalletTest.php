<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\BuckarooWallet\Models;

use Buckaroo\PaymentMethods\BuckarooWallet\Models\Wallet;
use Buckaroo\PaymentMethods\BuckarooWallet\Service\ParameterKeys\BankAccountAdapter;
use Buckaroo\PaymentMethods\BuckarooWallet\Service\ParameterKeys\CustomerAdapter;
use Buckaroo\PaymentMethods\BuckarooWallet\Service\ParameterKeys\EmailAdapter;
use Tests\TestCase;

class WalletTest extends TestCase
{
    /** @test */
    public function it_sets_and_returns_customer_adapter(): void
    {
        $wallet = new Wallet([
            'customer' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
        ]);

        $this->assertInstanceOf(CustomerAdapter::class, $wallet->customer());
    }

    /** @test */
    public function it_returns_existing_customer_when_called_without_data(): void
    {
        $wallet = new Wallet([
            'customer' => [
                'firstName' => 'Jane',
                'lastName' => 'Smith',
            ],
        ]);

        $customer = $wallet->customer();
        $sameCustomer = $wallet->customer();

        $this->assertSame($customer, $sameCustomer);
    }

    /** @test */
    public function it_sets_and_returns_email_adapter(): void
    {
        $wallet = new Wallet([
            'email' => 'test@example.com',
        ]);

        $this->assertInstanceOf(EmailAdapter::class, $wallet->email());
    }

    /** @test */
    public function it_returns_existing_email_when_called_without_data(): void
    {
        $wallet = new Wallet([
            'email' => 'user@example.com',
        ]);

        $email = $wallet->email();
        $sameEmail = $wallet->email();

        $this->assertSame($email, $sameEmail);
    }

    /** @test */
    public function it_sets_and_returns_bank_account_adapter(): void
    {
        $wallet = new Wallet([
            'bankAccount' => [
                'iban' => 'NL91ABNA0417164300',
                'bic' => 'ABNANL2A',
            ],
        ]);

        $this->assertInstanceOf(BankAccountAdapter::class, $wallet->bankAccount());
    }

    /** @test */
    public function it_returns_existing_bank_account_when_called_without_data(): void
    {
        $wallet = new Wallet([
            'bankAccount' => [
                'iban' => 'NL91ABNA0417164300',
            ],
        ]);

        $bankAccount = $wallet->bankAccount();
        $sameBankAccount = $wallet->bankAccount();

        $this->assertSame($bankAccount, $sameBankAccount);
    }

    /** @test */
    public function it_sets_wallet_properties_from_constructor(): void
    {
        $wallet = new Wallet([
            'walletId' => 'WALLET-123',
            'status' => 'active',
            'walletMutationGuid' => 'MUTATION-GUID-123',
        ]);

        $this->assertSame('WALLET-123', $wallet->walletId);
        $this->assertSame('active', $wallet->status);
        $this->assertSame('MUTATION-GUID-123', $wallet->walletMutationGuid);
    }
}
