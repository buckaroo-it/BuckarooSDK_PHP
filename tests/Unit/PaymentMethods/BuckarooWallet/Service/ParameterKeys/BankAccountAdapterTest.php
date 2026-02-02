<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\BuckarooWallet\Service\ParameterKeys;

use Buckaroo\Models\BankAccount;
use Buckaroo\PaymentMethods\BuckarooWallet\Service\ParameterKeys\BankAccountAdapter;
use Buckaroo\PaymentMethods\BuckarooWallet\Service\ParameterKeys\CustomerAdapter;
use Tests\TestCase;

class BankAccountAdapterTest extends TestCase
{
    public function test_extends_customer_adapter(): void
    {
        $bankAccount = new BankAccount(['iban' => 'NL91ABNA0417164300']);
        $adapter = new BankAccountAdapter($bankAccount);

        $this->assertInstanceOf(CustomerAdapter::class, $adapter);
    }

    public function test_transforms_iban_to_consumer_iban(): void
    {
        $bankAccount = new BankAccount(['iban' => 'NL91ABNA0417164300']);
        $adapter = new BankAccountAdapter($bankAccount);

        $this->assertSame('ConsumerIban', $adapter->serviceParameterKeyOf('iban'));
    }

    public function test_proxies_property_access_to_model(): void
    {
        $bankAccount = new BankAccount([
            'iban' => 'NL91ABNA0417164300',
            'accountName' => 'J. Doe',
            'bic' => 'ABNANL2A',
        ]);

        $adapter = new BankAccountAdapter($bankAccount);

        $this->assertSame('NL91ABNA0417164300', $adapter->iban);
        $this->assertSame('J. Doe', $adapter->accountName);
        $this->assertSame('ABNANL2A', $adapter->bic);
    }

    public function test_delegates_to_array_to_model(): void
    {
        $bankAccount = new BankAccount([
            'iban' => 'DE89370400440532013000',
            'bic' => 'COBADEFFXXX',
        ]);

        $adapter = new BankAccountAdapter($bankAccount);
        $array = $adapter->toArray();

        $this->assertIsArray($array);
        $this->assertSame('DE89370400440532013000', $array['iban']);
        $this->assertSame('COBADEFFXXX', $array['bic']);
    }

    public function test_handles_various_iban_formats(): void
    {
        $ibans = [
            'NL91ABNA0417164300',
            'DE89370400440532013000',
            'GB29NWBK60161331926819',
            'FR1420041010050500013M02606',
            'BE68539007547034',
        ];

        foreach ($ibans as $iban) {
            $bankAccount = new BankAccount(['iban' => $iban]);
            $adapter = new BankAccountAdapter($bankAccount);

            $this->assertSame($iban, $adapter->iban);
            $this->assertSame('ConsumerIban', $adapter->serviceParameterKeyOf('iban'));
        }
    }

    public function test_uses_ucfirst_for_unmapped_properties(): void
    {
        $bankAccount = new BankAccount(['accountName' => 'Test']);
        $adapter = new BankAccountAdapter($bankAccount);

        $this->assertSame('AccountName', $adapter->serviceParameterKeyOf('accountName'));
        $this->assertSame('Bic', $adapter->serviceParameterKeyOf('bic'));
    }
}
