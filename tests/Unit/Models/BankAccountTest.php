<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use Buckaroo\Models\BankAccount;
use Tests\TestCase;

class BankAccountTest extends TestCase
{
    public function test_initializes_all_properties_from_constructor(): void
    {
        $bankAccount = new BankAccount([
            'iban' => 'NL91ABNA0417164300',
            'accountName' => 'John Doe',
            'bic' => 'ABNANL2A',
        ]);

        $this->assertSame('NL91ABNA0417164300', $bankAccount->iban);
        $this->assertSame('John Doe', $bankAccount->accountName);
        $this->assertSame('ABNANL2A', $bankAccount->bic);
    }

    public function test_handles_partial_initialization(): void
    {
        $bankAccount = new BankAccount([
            'iban' => 'DE89370400440532013000',
        ]);

        $this->assertSame('DE89370400440532013000', $bankAccount->iban);
    }

    public function test_to_array_preserves_string_types(): void
    {
        $bankAccount = new BankAccount([
            'iban' => 'BE68539007547034',
            'accountName' => 'Jane Smith',
            'bic' => 'KREDBEBB',
        ]);

        $array = $bankAccount->toArray();

        $this->assertIsString($array['iban']);
        $this->assertIsString($array['accountName']);
        $this->assertIsString($array['bic']);

        $this->assertSame('BE68539007547034', $array['iban']);
        $this->assertSame('Jane Smith', $array['accountName']);
        $this->assertSame('KREDBEBB', $array['bic']);
    }

    public function test_handles_empty_strings(): void
    {
        $bankAccount = new BankAccount([
            'iban' => '',
            'accountName' => '',
            'bic' => '',
        ]);

        $this->assertSame('', $bankAccount->iban);
        $this->assertSame('', $bankAccount->accountName);
        $this->assertSame('', $bankAccount->bic);

        $array = $bankAccount->toArray();
        $this->assertSame('', $array['iban']);
        $this->assertSame('', $array['accountName']);
        $this->assertSame('', $array['bic']);
    }

    public function test_handles_special_characters_in_account_name(): void
    {
        $bankAccount = new BankAccount([
            'iban' => 'FR1420041010050500013M02606',
            'accountName' => 'François O\'Brien-Müller & Co., "Trading" Ltd.',
            'bic' => 'BNPAFRPP',
        ]);

        $this->assertSame('François O\'Brien-Müller & Co., "Trading" Ltd.', $bankAccount->accountName);

        $array = $bankAccount->toArray();
        $this->assertSame('François O\'Brien-Müller & Co., "Trading" Ltd.', $array['accountName']);
    }

    public function test_accepts_various_iban_formats(): void
    {
        $testCases = [
            ['iban' => 'NL91ABNA0417164300', 'country' => 'NL'],
            ['iban' => 'DE89370400440532013000', 'country' => 'DE'],
            ['iban' => 'GB29NWBK60161331926819', 'country' => 'GB'],
            ['iban' => 'IT60X0542811101000000123456', 'country' => 'IT'],
            ['iban' => 'ES9121000418450200051332', 'country' => 'ES'],
        ];

        foreach ($testCases as $testCase) {
            $bankAccount = new BankAccount(['iban' => $testCase['iban']]);
            $this->assertSame($testCase['iban'], $bankAccount->iban);
            $this->assertSame($testCase['iban'], $bankAccount->toArray()['iban']);
        }
    }
}
