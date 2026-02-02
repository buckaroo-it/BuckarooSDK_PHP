<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Afterpay\Service\ParameterKeys;

use Buckaroo\Models\Address;
use Buckaroo\PaymentMethods\Afterpay\Service\ParameterKeys\AddressAdapter;
use Tests\TestCase;

class AddressAdapterTest extends TestCase
{
    public function test_transforms_house_number_to_street_number(): void
    {
        $address = new Address(['houseNumber' => '123']);
        $adapter = new AddressAdapter($address);

        $this->assertSame('StreetNumber', $adapter->serviceParameterKeyOf('houseNumber'));
    }

    public function test_transforms_house_number_additional_to_street_number_additional(): void
    {
        $address = new Address(['houseNumberAdditional' => 'A']);
        $adapter = new AddressAdapter($address);

        $this->assertSame('StreetNumberAdditional', $adapter->serviceParameterKeyOf('houseNumberAdditional'));
    }

    public function test_transforms_zipcode_to_postal_code(): void
    {
        $address = new Address(['zipcode' => '1234AB']);
        $adapter = new AddressAdapter($address);

        $this->assertSame('PostalCode', $adapter->serviceParameterKeyOf('zipcode'));
    }

    public function test_uses_ucfirst_for_unmapped_properties(): void
    {
        $address = new Address(['street' => 'Main Street']);
        $adapter = new AddressAdapter($address);

        $this->assertSame('Street', $adapter->serviceParameterKeyOf('street'));
        $this->assertSame('City', $adapter->serviceParameterKeyOf('city'));
        $this->assertSame('Country', $adapter->serviceParameterKeyOf('country'));
    }

    public function test_proxies_property_access_to_model(): void
    {
        $address = new Address([
            'street' => 'Main Street',
            'houseNumber' => '456',
            'houseNumberAdditional' => 'B',
            'zipcode' => '5678CD',
            'city' => 'Amsterdam',
            'country' => 'NL',
        ]);

        $adapter = new AddressAdapter($address);

        $this->assertSame('Main Street', $adapter->street);
        $this->assertSame('456', $adapter->houseNumber);
        $this->assertSame('B', $adapter->houseNumberAdditional);
        $this->assertSame('5678CD', $adapter->zipcode);
        $this->assertSame('Amsterdam', $adapter->city);
        $this->assertSame('NL', $adapter->country);
    }

    public function test_delegates_to_array_to_model(): void
    {
        $address = new Address([
            'street' => 'Test Street',
            'houseNumber' => '789',
            'zipcode' => '9012EF',
            'city' => 'Rotterdam',
        ]);

        $adapter = new AddressAdapter($address);
        $array = $adapter->toArray();

        $this->assertIsArray($array);
        $this->assertSame('Test Street', $array['street']);
        $this->assertSame('789', $array['houseNumber']);
        $this->assertSame('9012EF', $array['zipcode']);
        $this->assertSame('Rotterdam', $array['city']);
    }

    public function test_handles_partial_address_data(): void
    {
        $address = new Address([
            'street' => 'Minimal Street',
            'houseNumber' => '1',
            'city' => 'Utrecht',
        ]);

        $adapter = new AddressAdapter($address);

        $this->assertSame('Minimal Street', $adapter->street);
        $this->assertSame('1', $adapter->houseNumber);
        $this->assertSame('Utrecht', $adapter->city);
        $this->assertNull($adapter->zipcode);
        $this->assertNull($adapter->houseNumberAdditional);
    }

    public function test_all_key_mappings_are_correct(): void
    {
        $address = new Address([
            'houseNumber' => '100',
            'houseNumberAdditional' => 'C',
            'zipcode' => '1111AA',
        ]);

        $adapter = new AddressAdapter($address);

        $expectedMappings = [
            'houseNumber' => 'StreetNumber',
            'houseNumberAdditional' => 'StreetNumberAdditional',
            'zipcode' => 'PostalCode',
        ];

        foreach ($expectedMappings as $property => $expectedKey) {
            $this->assertSame($expectedKey, $adapter->serviceParameterKeyOf($property));
        }
    }
}
