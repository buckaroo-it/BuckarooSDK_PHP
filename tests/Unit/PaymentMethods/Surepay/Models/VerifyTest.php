<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Surepay\Models;

use Buckaroo\PaymentMethods\Surepay\Models\Verify;
use Buckaroo\PaymentMethods\Surepay\Service\ParameterKeys\BankAccountAdapter;
use Tests\TestCase;

class VerifyTest extends TestCase
{
    /** @test */
    public function it_sets_bank_account_from_array(): void
    {
        $verify = new Verify([
            'bankAccount' => [
                'iban' => 'NL91ABNA0417164300',
                'bic' => 'ABNANL2A',
                'accountName' => 'John Doe',
            ],
        ]);

        $bankAccount = $verify->bankAccount();

        $this->assertInstanceOf(BankAccountAdapter::class, $bankAccount);
    }

    /** @test */
    public function it_returns_bank_account_without_parameter(): void
    {
        $verify = new Verify([
            'bankAccount' => [
                'iban' => 'NL91ABNA0417164300',
            ],
        ]);

        $bankAccount = $verify->bankAccount();
        $this->assertInstanceOf(BankAccountAdapter::class, $bankAccount);

        $sameBankAccount = $verify->bankAccount(null);
        $this->assertSame($bankAccount, $sameBankAccount);
    }
}
