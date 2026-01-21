<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Adapters;

use Buckaroo\Models\Address;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Billink\Service\ParameterKeys\AddressAdapter as BillinkAddressAdapter;
use Buckaroo\PaymentMethods\In3Old\Service\ParameterKeys\AddressAdapter as In3OldAddressAdapter;
use Tests\TestCase;

class ServiceParametersKeysAdapterTest extends TestCase
{
    public function test_extends_service_parameter(): void
    {
        $address = new Address(['street' => 'Test Street']);
        $adapter = new BillinkAddressAdapter($address);

        $this->assertInstanceOf(ServiceParameter::class, $adapter);
    }

    public function test_proxies_property_access_to_wrapped_model(): void
    {
        $address = new Address([
            'street' => 'Main Street',
            'houseNumber' => '123',
            'city' => 'Amsterdam',
        ]);

        $adapter = new BillinkAddressAdapter($address);

        $this->assertEquals('Main Street', $adapter->street);
        $this->assertEquals('123', $adapter->houseNumber);
        $this->assertEquals('Amsterdam', $adapter->city);
    }

    public function test_returns_null_for_non_existent_property(): void
    {
        $address = new Address(['street' => 'Test Street']);
        $adapter = new BillinkAddressAdapter($address);

        $this->assertNull($adapter->nonExistentProperty);
    }

    public function test_returns_null_for_unset_property(): void
    {
        $address = new Address(['street' => 'Test Street']);
        $adapter = new BillinkAddressAdapter($address);

        // city was not set
        $this->assertNull($adapter->city);
    }

    public function test_transforms_property_key_using_custom_mapping(): void
    {
        $address = new Address(['houseNumber' => '456']);

        // Billink adapter maps houseNumber -> StreetNumber
        $billinkAdapter = new BillinkAddressAdapter($address);
        $this->assertEquals('StreetNumber', $billinkAdapter->serviceParameterKeyOf('houseNumber'));

        // In3Old adapter maps houseNumber -> HouseNumber (no change) but uses default ucfirst
        $in3OldAdapter = new In3OldAddressAdapter($address);
        $this->assertEquals('HouseNumber', $in3OldAdapter->serviceParameterKeyOf('houseNumber'));
    }

    public function test_uses_ucfirst_for_unmapped_keys(): void
    {
        $address = new Address(['street' => 'Test Street']);
        $adapter = new BillinkAddressAdapter($address);

        // street is not in the keys array, so it should use ucfirst
        $this->assertEquals('Street', $adapter->serviceParameterKeyOf('street'));
        $this->assertEquals('City', $adapter->serviceParameterKeyOf('city'));
        $this->assertEquals('Country', $adapter->serviceParameterKeyOf('country'));
    }

    public function test_billink_adapter_key_mappings(): void
    {
        $address = new Address([
            'houseNumber' => '123',
            'houseNumberAdditional' => 'A',
            'zipcode' => '1234AB',
        ]);

        $adapter = new BillinkAddressAdapter($address);

        // Test Billink-specific mappings
        $this->assertEquals('StreetNumber', $adapter->serviceParameterKeyOf('houseNumber'));
        $this->assertEquals('StreetNumberAdditional', $adapter->serviceParameterKeyOf('houseNumberAdditional'));
        $this->assertEquals('PostalCode', $adapter->serviceParameterKeyOf('zipcode'));
    }

    public function test_in3old_adapter_key_mappings(): void
    {
        $address = new Address([
            'houseNumberAdditional' => 'B',
            'zipcode' => '5678CD',
        ]);

        $adapter = new In3OldAddressAdapter($address);

        // Test In3Old-specific mappings
        $this->assertEquals('HouseNumberSuffix', $adapter->serviceParameterKeyOf('houseNumberAdditional'));
        $this->assertEquals('ZipCode', $adapter->serviceParameterKeyOf('zipcode'));
    }

    public function test_delegates_to_array_to_wrapped_model(): void
    {
        $address = new Address([
            'street' => 'Test Street',
            'houseNumber' => '789',
            'city' => 'Utrecht',
        ]);

        $adapter = new BillinkAddressAdapter($address);
        $array = $adapter->toArray();

        $this->assertIsArray($array);
        $this->assertEquals('Test Street', $array['street']);
        $this->assertEquals('789', $array['houseNumber']);
        $this->assertEquals('Utrecht', $array['city']);
    }

    public function test_delegates_get_object_vars_to_wrapped_model(): void
    {
        $address = new Address([
            'street' => 'Object Street',
            'country' => 'NL',
        ]);

        $adapter = new BillinkAddressAdapter($address);
        $vars = $adapter->getObjectVars();

        $this->assertIsArray($vars);
        $this->assertArrayHasKey('street', $vars);
        $this->assertArrayHasKey('country', $vars);
        $this->assertEquals('Object Street', $vars['street']);
        $this->assertEquals('NL', $vars['country']);
    }

    public function test_works_with_empty_model(): void
    {
        $address = new Address();
        $adapter = new BillinkAddressAdapter($address);

        $this->assertNull($adapter->street);
        $this->assertEmpty($adapter->toArray());
    }

    public function test_preserves_original_model_data(): void
    {
        $originalData = [
            'street' => 'Original Street',
            'houseNumber' => '100',
            'zipcode' => '9999ZZ',
            'city' => 'Den Haag',
            'country' => 'NL',
        ];

        $address = new Address($originalData);
        $adapter = new BillinkAddressAdapter($address);

        // Verify all data is preserved
        foreach ($originalData as $key => $value) {
            $this->assertEquals($value, $adapter->$key, "Property {$key} should match");
        }
    }

    public function test_handles_special_characters_in_values(): void
    {
        $address = new Address([
            'street' => "Strasse mit Umlauten \u00e4\u00f6\u00fc",
            'houseNumber' => '1-3',
            'city' => "Saint-\u00c9tienne",
        ]);

        $adapter = new BillinkAddressAdapter($address);

        $this->assertEquals("Strasse mit Umlauten \u00e4\u00f6\u00fc", $adapter->street);
        $this->assertEquals('1-3', $adapter->houseNumber);
        $this->assertEquals("Saint-\u00c9tienne", $adapter->city);
    }

    public function test_multiple_adapters_can_wrap_same_model(): void
    {
        $address = new Address([
            'street' => 'Shared Street',
            'houseNumber' => '50',
            'zipcode' => '1000AA',
        ]);

        $billinkAdapter = new BillinkAddressAdapter($address);
        $in3OldAdapter = new In3OldAddressAdapter($address);

        // Both should access the same underlying data
        $this->assertEquals($billinkAdapter->street, $in3OldAdapter->street);
        $this->assertEquals($billinkAdapter->houseNumber, $in3OldAdapter->houseNumber);
        $this->assertEquals($billinkAdapter->zipcode, $in3OldAdapter->zipcode);

        // But key mappings should differ
        $this->assertNotEquals(
            $billinkAdapter->serviceParameterKeyOf('zipcode'),
            $in3OldAdapter->serviceParameterKeyOf('zipcode')
        );
    }
}
