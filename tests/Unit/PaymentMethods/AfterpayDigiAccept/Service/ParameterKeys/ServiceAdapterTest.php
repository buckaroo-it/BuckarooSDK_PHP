<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\AfterpayDigiAccept\Service\ParameterKeys;

use Buckaroo\Models\Address;
use Buckaroo\PaymentMethods\AfterpayDigiAccept\Service\ParameterKeys\ServiceAdapter;
use Tests\TestCase;

class ServiceAdapterTest extends TestCase
{
    public function test_prepends_prefix_to_keys(): void
    {
        $address = new Address(['houseNumber' => '123']);
        $adapter = new ServiceAdapter('BillingCustomer', $address);

        // ServiceAdapter has no key mappings, just uses ucfirst
        $this->assertSame('BillingCustomerHouseNumber', $adapter->serviceParameterKeyOf('houseNumber'));
    }

    public function test_prepends_prefix_to_unmapped_keys(): void
    {
        $address = new Address(['street' => 'Main Street']);
        $adapter = new ServiceAdapter('ShippingCustomer', $address);

        $this->assertSame('ShippingCustomerStreet', $adapter->serviceParameterKeyOf('street'));
        $this->assertSame('ShippingCustomerCity', $adapter->serviceParameterKeyOf('city'));
    }

    public function test_uses_ucfirst_with_prefix(): void
    {
        $address = new Address([
            'houseNumber' => '456',
            'houseNumberAdditional' => 'B',
            'zipcode' => '1234AB',
        ]);

        $adapter = new ServiceAdapter('Customer', $address);

        // ServiceAdapter uses ucfirst for all properties, no special mappings
        $this->assertSame('CustomerHouseNumber', $adapter->serviceParameterKeyOf('houseNumber'));
        $this->assertSame('CustomerHouseNumberAdditional', $adapter->serviceParameterKeyOf('houseNumberAdditional'));
        $this->assertSame('CustomerZipcode', $adapter->serviceParameterKeyOf('zipcode'));
    }

    public function test_works_with_empty_prefix(): void
    {
        $address = new Address(['houseNumber' => '789']);
        $adapter = new ServiceAdapter('', $address);

        $this->assertSame('HouseNumber', $adapter->serviceParameterKeyOf('houseNumber'));
        $this->assertSame('Street', $adapter->serviceParameterKeyOf('street'));
    }

    public function test_proxies_property_access_to_model(): void
    {
        $address = new Address([
            'street' => 'Test Street',
            'houseNumber' => '100',
            'city' => 'Amsterdam',
        ]);

        $adapter = new ServiceAdapter('Billing', $address);

        $this->assertSame('Test Street', $adapter->street);
        $this->assertSame('100', $adapter->houseNumber);
        $this->assertSame('Amsterdam', $adapter->city);
    }

    public function test_delegates_to_array_to_model(): void
    {
        $address = new Address([
            'street' => 'Array Street',
            'houseNumber' => '200',
        ]);

        $adapter = new ServiceAdapter('Prefix', $address);
        $array = $adapter->toArray();

        $this->assertIsArray($array);
        $this->assertSame('Array Street', $array['street']);
        $this->assertSame('200', $array['houseNumber']);
    }

    public function test_different_prefixes_produce_different_keys(): void
    {
        $address = new Address(['houseNumber' => '123']);

        $billingAdapter = new ServiceAdapter('Billing', $address);
        $shippingAdapter = new ServiceAdapter('Shipping', $address);

        $billingKey = $billingAdapter->serviceParameterKeyOf('houseNumber');
        $shippingKey = $shippingAdapter->serviceParameterKeyOf('houseNumber');

        $this->assertSame('BillingHouseNumber', $billingKey);
        $this->assertSame('ShippingHouseNumber', $shippingKey);
        $this->assertNotEquals($billingKey, $shippingKey);
    }

    public function test_handles_multiple_properties_with_prefix(): void
    {
        $address = new Address([
            'street' => 'Prefix Street',
            'houseNumber' => '50',
            'houseNumberAdditional' => 'A',
            'zipcode' => '5678CD',
            'city' => 'Utrecht',
            'country' => 'NL',
        ]);

        $adapter = new ServiceAdapter('Customer', $address);

        // ServiceAdapter uses ucfirst for all properties
        $expectedTransformations = [
            'street' => 'CustomerStreet',
            'houseNumber' => 'CustomerHouseNumber',
            'houseNumberAdditional' => 'CustomerHouseNumberAdditional',
            'zipcode' => 'CustomerZipcode',
            'city' => 'CustomerCity',
            'country' => 'CustomerCountry',
        ];

        foreach ($expectedTransformations as $property => $expectedKey) {
            $this->assertSame($expectedKey, $adapter->serviceParameterKeyOf($property));
        }
    }

    public function test_prefix_is_case_sensitive(): void
    {
        $address = new Address(['city' => 'TestCity']);

        $lowerAdapter = new ServiceAdapter('customer', $address);
        $upperAdapter = new ServiceAdapter('Customer', $address);

        $this->assertSame('customerCity', $lowerAdapter->serviceParameterKeyOf('city'));
        $this->assertSame('CustomerCity', $upperAdapter->serviceParameterKeyOf('city'));
    }
}
