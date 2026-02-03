<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Trustly\Service\ParameterKeys;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\Trustly\Service\ParameterKeys\PayAdapter;
use Tests\TestCase;

class PayAdapterTest extends TestCase
{
    private function createPayModel(array $data): Model
    {
        return new class($data) extends Model {
            protected string $country;
            protected string $currency;
            protected float $amount;
        };
    }

    public function test_transforms_country_to_customer_country_code(): void
    {
        $pay = $this->createPayModel(['country' => 'NL']);
        $adapter = new PayAdapter($pay);

        $this->assertSame('CustomerCountryCode', $adapter->serviceParameterKeyOf('country'));
    }

    public function test_uses_ucfirst_for_unmapped_properties(): void
    {
        $pay = $this->createPayModel(['currency' => 'EUR']);
        $adapter = new PayAdapter($pay);

        $this->assertSame('Currency', $adapter->serviceParameterKeyOf('currency'));
        $this->assertSame('Amount', $adapter->serviceParameterKeyOf('amount'));
    }

    public function test_proxies_property_access_to_model(): void
    {
        $pay = $this->createPayModel([
            'country' => 'DE',
            'currency' => 'EUR',
            'amount' => 100.50,
        ]);

        $adapter = new PayAdapter($pay);

        $this->assertSame('DE', $adapter->country);
        $this->assertSame('EUR', $adapter->currency);
        $this->assertSame(100.50, $adapter->amount);
    }

    public function test_delegates_to_array_to_model(): void
    {
        $pay = $this->createPayModel([
            'country' => 'SE',
            'amount' => 250.00,
        ]);

        $adapter = new PayAdapter($pay);
        $array = $adapter->toArray();

        $this->assertIsArray($array);
        $this->assertSame('SE', $array['country']);
        $this->assertSame(250.00, $array['amount']);
    }

    public function test_handles_various_country_codes(): void
    {
        $countries = ['NL', 'DE', 'SE', 'FI', 'DK', 'NO', 'AT', 'BE'];

        foreach ($countries as $countryCode) {
            $pay = $this->createPayModel(['country' => $countryCode]);
            $adapter = new PayAdapter($pay);

            $this->assertSame($countryCode, $adapter->country);
            $this->assertSame('CustomerCountryCode', $adapter->serviceParameterKeyOf('country'));
        }
    }
}
