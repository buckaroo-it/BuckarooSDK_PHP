<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use Buckaroo\Models\Address;
use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceList;
use Buckaroo\Models\Services;
use Tests\TestCase;

class ModelTest extends TestCase
{
    public function test_sets_properties_from_array_in_constructor(): void
    {
        $address = new Address([
            'street' => 'Main Street',
            'houseNumber' => '123',
            'city' => 'Amsterdam',
            'country' => 'NL',
        ]);

        $this->assertSame('Main Street', $address->street);
        $this->assertSame('123', $address->houseNumber);
        $this->assertSame('Amsterdam', $address->city);
        $this->assertSame('NL', $address->country);
    }

    public function test_provides_magic_property_access(): void
    {
        $address = new Address([
            'street' => 'Test Street',
            'zipcode' => '1234AB',
        ]);

        $this->assertSame('Test Street', $address->street);
        $this->assertSame('1234AB', $address->zipcode);
    }

    public function test_returns_null_for_missing_properties(): void
    {
        $address = new Address([
            'street' => 'Test Street',
        ]);

        $this->assertNull($address->city);
        $this->assertNull($address->country);
        $this->assertNull($address->nonExistentProperty);
    }

    public function test_sets_properties_via_magic_set(): void
    {
        $address = new Address();

        $address->street = 'New Street';
        $address->houseNumber = '456';
        $address->city = 'Rotterdam';

        $this->assertSame('New Street', $address->street);
        $this->assertSame('456', $address->houseNumber);
        $this->assertSame('Rotterdam', $address->city);
    }

    public function test_ignores_setting_non_existent_properties(): void
    {
        $address = new Address();

        $address->nonExistentProperty = 'value';

        $this->assertNull($address->nonExistentProperty);
    }

    public function test_converts_to_array(): void
    {
        $address = new Address([
            'street' => 'Main Street',
            'houseNumber' => '123',
            'city' => 'Amsterdam',
            'country' => 'NL',
        ]);

        $array = $address->toArray();

        $this->assertIsArray($array);
        $this->assertSame('Main Street', $array['street']);
        $this->assertSame('123', $array['houseNumber']);
        $this->assertSame('Amsterdam', $array['city']);
        $this->assertSame('NL', $array['country']);
    }

    public function test_transforms_property_names_to_ucfirst(): void
    {
        $address = new Address();

        $this->assertSame('Street', $address->serviceParameterKeyOf('street'));
        $this->assertSame('HouseNumber', $address->serviceParameterKeyOf('houseNumber'));
        $this->assertSame('City', $address->serviceParameterKeyOf('city'));
        $this->assertSame('Zipcode', $address->serviceParameterKeyOf('zipcode'));
    }

    public function test_gets_object_vars(): void
    {
        $address = new Address([
            'street' => 'Main Street',
            'city' => 'Amsterdam',
        ]);

        $vars = $address->getObjectVars();

        $this->assertIsArray($vars);
        $this->assertArrayHasKey('street', $vars);
        $this->assertArrayHasKey('city', $vars);
        $this->assertSame('Main Street', $vars['street']);
        $this->assertSame('Amsterdam', $vars['city']);
    }

    public function test_can_set_properties_after_construction(): void
    {
        $address = new Address();

        $address->setProperties([
            'street' => 'Updated Street',
            'houseNumber' => '789',
            'city' => 'Utrecht',
        ]);

        $this->assertSame('Updated Street', $address->street);
        $this->assertSame('789', $address->houseNumber);
        $this->assertSame('Utrecht', $address->city);
    }

    public function test_handles_null_in_constructor(): void
    {
        $address = new Address(null);

        $this->assertNull($address->street);
        $this->assertNull($address->city);
    }

    public function test_handles_null_in_set_properties(): void
    {
        $address = new Address([
            'street' => 'Initial Street',
        ]);

        $address->setProperties(null);

        $this->assertSame('Initial Street', $address->street);
    }

    public function test_returns_self_from_set_properties(): void
    {
        $address = new Address();

        $result = $address->setProperties([
            'street' => 'Test',
        ]);

        $this->assertSame($address, $result);
    }

    public function test_returns_self_from_magic_set(): void
    {
        $address = new Address();

        $result = $address->__set('street', 'Test Street');

        $this->assertSame($address, $result);
    }

    public function test_handles_empty_array_in_set_properties(): void
    {
        $address = new Address([
            'street' => 'Original Street',
            'city' => 'Original City',
        ]);

        $address->setProperties([]);

        $this->assertSame('Original Street', $address->street);
        $this->assertSame('Original City', $address->city);
    }

    public function test_overwrites_properties_with_set_properties(): void
    {
        $address = new Address([
            'street' => 'First Street',
            'city' => 'First City',
        ]);

        $address->setProperties([
            'street' => 'Second Street',
            'city' => 'Second City',
        ]);

        $this->assertSame('Second Street', $address->street);
        $this->assertSame('Second City', $address->city);
    }

    public function test_setting_nullable_property_to_null_explicitly(): void
    {
        // Person has nullable properties like initials and birthDate
        $person = new Person([
            'firstName' => 'John',
            'initials' => 'J.D.',
            'birthDate' => '1990-01-01',
        ]);

        $person->initials = null;
        $person->birthDate = null;

        $this->assertNull($person->initials);
        $this->assertNull($person->birthDate);
    }

    public function test_chaining_magic_set_calls(): void
    {
        $address = new Address();

        $result = $address->__set('street', 'Street 1')
            ->__set('city', 'City 1')
            ->__set('country', 'NL');

        $this->assertSame($address, $result);
        $this->assertSame('Street 1', $address->street);
        $this->assertSame('City 1', $address->city);
        $this->assertSame('NL', $address->country);
    }

    public function test_converts_nested_arrayable_objects_to_array(): void
    {
        $services = new Services();

        $address1 = new Address([
            'street' => 'Street 1',
            'city' => 'City 1',
        ]);

        $address2 = new Address([
            'street' => 'Street 2',
            'city' => 'City 2',
        ]);

        $services->setProperties([
            'ServiceList' => [$address1, $address2],
        ]);

        $array = $services->toArray();

        $this->assertIsArray($array);
        $this->assertIsArray($array['ServiceList']);
        $this->assertCount(2, $array['ServiceList']);

        $this->assertIsArray($array['ServiceList'][0]);
        $this->assertSame('Street 1', $array['ServiceList'][0]['street']);
        $this->assertSame('City 1', $array['ServiceList'][0]['city']);

        $this->assertIsArray($array['ServiceList'][1]);
        $this->assertSame('Street 2', $array['ServiceList'][1]['street']);
        $this->assertSame('City 2', $array['ServiceList'][1]['city']);
    }

    public function test_converts_nested_plain_arrays_to_array(): void
    {
        // Services has ServiceList which can contain nested arrays
        $services = new Services();

        $services->setProperties([
            'ServiceList' => [
                'level1' => [
                    'level2' => 'deep value',
                    'level2b' => ['level3' => 'deeper'],
                ],
            ],
        ]);

        $array = $services->toArray();

        $this->assertIsArray($array);
        $this->assertIsArray($array['ServiceList']);
        $this->assertIsArray($array['ServiceList']['level1']);
        $this->assertSame('deep value', $array['ServiceList']['level1']['level2']);
        $this->assertSame('deeper', $array['ServiceList']['level1']['level2b']['level3']);
    }

    public function test_converts_deeply_nested_structures_to_array(): void
    {
        $services = new Services();

        $innerAddress = new Address([
            'street' => 'Inner Street',
            'city' => 'Inner City',
        ]);

        $middleData = [
            'address' => $innerAddress,
            'metadata' => [
                'created' => '2024-01-01',
                'updated' => '2024-01-02',
            ],
        ];

        $services->setProperties([
            'ServiceList' => [
                'outer' => $middleData,
            ],
        ]);

        $array = $services->toArray();

        $this->assertIsArray($array['ServiceList']['outer']['address']);
        $this->assertSame('Inner Street', $array['ServiceList']['outer']['address']['street']);
        $this->assertSame('Inner City', $array['ServiceList']['outer']['address']['city']);
        $this->assertIsArray($array['ServiceList']['outer']['metadata']);
        $this->assertSame('2024-01-01', $array['ServiceList']['outer']['metadata']['created']);
    }

    public function test_to_array_with_empty_model(): void
    {
        $address = new Address();

        $array = $address->toArray();

        // In PHP 8+, uninitialized typed properties are not included in get_object_vars()
        $this->assertIsArray($array);
        $this->assertEmpty($array);
    }

    public function test_to_array_with_nullable_values(): void
    {
        // Person has nullable properties (initials, birthDate)
        $person = new Person([
            'firstName' => 'John',
            'lastName' => 'Doe',
            'initials' => null,
            'birthDate' => null,
        ]);

        $array = $person->toArray();

        $this->assertIsArray($array);
        $this->assertSame('John', $array['firstName']);
        $this->assertSame('Doe', $array['lastName']);
        $this->assertNull($array['initials']);
        $this->assertNull($array['birthDate']);
    }

    public function test_to_array_preserves_null_in_nested_arrays(): void
    {
        $services = new Services();
        $services->setProperties([
            'ServiceList' => [
                'metadata' => [
                    'valid' => true,
                    'verified' => null,
                    'score' => 100,
                ],
            ],
        ]);

        $array = $services->toArray();

        $this->assertIsArray($array['ServiceList']['metadata']);
        $this->assertTrue($array['ServiceList']['metadata']['valid']);
        $this->assertNull($array['ServiceList']['metadata']['verified']);
        $this->assertSame(100, $array['ServiceList']['metadata']['score']);
    }

    public function test_service_parameter_key_handles_already_capitalized(): void
    {
        $address = new Address();

        $this->assertSame('Street', $address->serviceParameterKeyOf('Street'));
        $this->assertSame('ALLCAPS', $address->serviceParameterKeyOf('ALLCAPS'));
    }

    public function test_service_parameter_key_handles_empty_string(): void
    {
        $address = new Address();

        $this->assertSame('', $address->serviceParameterKeyOf(''));
    }

    public function test_service_parameter_key_handles_numeric_strings(): void
    {
        $address = new Address();

        $this->assertSame('123', $address->serviceParameterKeyOf('123'));
        $this->assertSame('456abc', $address->serviceParameterKeyOf('456abc'));
    }

    public function test_get_object_vars_includes_initialized_properties(): void
    {
        $address = new Address([
            'street' => 'Test Street',
            'city' => 'Amsterdam',
            'country' => 'NL',
        ]);

        $vars = $address->getObjectVars();

        // In PHP 8+, only initialized typed properties are included
        $this->assertArrayHasKey('street', $vars);
        $this->assertArrayHasKey('city', $vars);
        $this->assertArrayHasKey('country', $vars);
        $this->assertSame('Test Street', $vars['street']);
        $this->assertSame('Amsterdam', $vars['city']);
        $this->assertSame('NL', $vars['country']);

        // Uninitialized properties are not included in PHP 8
        $this->assertArrayNotHasKey('houseNumber', $vars);
        $this->assertArrayNotHasKey('zipcode', $vars);
    }
}
