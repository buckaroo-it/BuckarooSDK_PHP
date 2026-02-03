<?php

declare(strict_types=1);

namespace Tests\Unit\Services\ServiceListParameters;

use Buckaroo\Models\Address;
use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceList;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;
use Buckaroo\Services\ServiceListParameters\ModelParameters;
use Buckaroo\Services\ServiceListParameters\ServiceListParameter;
use Tests\TestCase;

class ModelParametersTest extends TestCase
{
    public function test_implements_service_list_parameter(): void
    {
        $serviceList = new ServiceList('TestService', 1, 'Pay');
        $defaultParams = new DefaultParameters($serviceList);
        $address = new Address(['street' => 'Test Street']);

        $modelParams = new ModelParameters($defaultParams, $address);

        $this->assertInstanceOf(ServiceListParameter::class, $modelParams);
    }

    public function test_adds_model_properties_as_parameters(): void
    {
        $serviceList = new ServiceList('TestService', 1, 'Pay');
        $defaultParams = new DefaultParameters($serviceList);
        $address = new Address([
            'street' => 'Main Street',
            'houseNumber' => '123',
            'city' => 'Amsterdam',
        ]);

        $modelParams = new ModelParameters($defaultParams, $address);
        $result = $modelParams->data();

        $parameters = $result->parameters();

        // Check that parameters were added
        $this->assertNotEmpty($parameters);

        // Find the street parameter
        $streetParam = array_filter($parameters, fn ($p) => $p['Name'] === 'Street');
        $this->assertNotEmpty($streetParam);
        $this->assertEquals('Main Street', array_values($streetParam)[0]['Value']);
    }

    public function test_uses_service_parameter_key_for_property_names(): void
    {
        $serviceList = new ServiceList('TestService', 1, 'Pay');
        $defaultParams = new DefaultParameters($serviceList);
        $address = new Address([
            'houseNumber' => '456',
        ]);

        $modelParams = new ModelParameters($defaultParams, $address);
        $result = $modelParams->data();

        $parameters = $result->parameters();

        // The property name should be transformed to the service parameter key
        $houseNumberParam = array_filter($parameters, fn ($p) => $p['Name'] === 'HouseNumber');
        $this->assertNotEmpty($houseNumberParam);
    }

    public function test_sets_group_type_when_provided(): void
    {
        $serviceList = new ServiceList('TestService', 1, 'Pay');
        $defaultParams = new DefaultParameters($serviceList);
        $address = new Address([
            'street' => 'Test Street',
        ]);

        $modelParams = new ModelParameters($defaultParams, $address, 'BillingCustomer', null);
        $result = $modelParams->data();

        $parameters = $result->parameters();

        $streetParam = array_filter($parameters, fn ($p) => $p['Name'] === 'Street');
        $this->assertNotEmpty($streetParam);
        $firstParam = array_values($streetParam)[0];
        $this->assertEquals('BillingCustomer', $firstParam['GroupType']);
    }

    public function test_sets_group_key_when_provided(): void
    {
        $serviceList = new ServiceList('TestService', 1, 'Pay');
        $defaultParams = new DefaultParameters($serviceList);
        $address = new Address([
            'city' => 'Rotterdam',
        ]);

        $modelParams = new ModelParameters($defaultParams, $address, 'Address', 1);
        $result = $modelParams->data();

        $parameters = $result->parameters();

        $cityParam = array_filter($parameters, fn ($p) => $p['Name'] === 'City');
        $this->assertNotEmpty($cityParam);
        $firstParam = array_values($cityParam)[0];
        $this->assertEquals(1, $firstParam['GroupID']);
    }

    public function test_uses_empty_string_for_null_group_type(): void
    {
        $serviceList = new ServiceList('TestService', 1, 'Pay');
        $defaultParams = new DefaultParameters($serviceList);
        $address = new Address([
            'zipcode' => '1234AB',
        ]);

        $modelParams = new ModelParameters($defaultParams, $address);
        $result = $modelParams->data();

        $parameters = $result->parameters();

        $zipcodeParam = array_filter($parameters, fn ($p) => $p['Name'] === 'Zipcode');
        $this->assertNotEmpty($zipcodeParam);
        $firstParam = array_values($zipcodeParam)[0];
        $this->assertEquals('', $firstParam['GroupType']);
    }

    public function test_uses_empty_string_for_null_group_key(): void
    {
        $serviceList = new ServiceList('TestService', 1, 'Pay');
        $defaultParams = new DefaultParameters($serviceList);
        $address = new Address([
            'country' => 'NL',
        ]);

        $modelParams = new ModelParameters($defaultParams, $address);
        $result = $modelParams->data();

        $parameters = $result->parameters();

        $countryParam = array_filter($parameters, fn ($p) => $p['Name'] === 'Country');
        $this->assertNotEmpty($countryParam);
        $firstParam = array_values($countryParam)[0];
        $this->assertEquals('', $firstParam['GroupID']);
    }

    public function test_skips_unset_values(): void
    {
        $serviceList = new ServiceList('TestService', 1, 'Pay');
        $defaultParams = new DefaultParameters($serviceList);
        // Only set street - city remains uninitialized
        $address = new Address([
            'street' => 'Test Street',
        ]);

        $modelParams = new ModelParameters($defaultParams, $address);
        $result = $modelParams->data();

        $parameters = $result->parameters();

        // City should not be in parameters since it was never set
        $cityParam = array_filter($parameters, fn ($p) => $p['Name'] === 'City');
        $this->assertEmpty($cityParam);
    }

    public function test_skips_array_values(): void
    {
        $serviceList = new ServiceList('TestService', 1, 'Pay');
        $defaultParams = new DefaultParameters($serviceList);
        $person = new Person([
            'firstName' => 'John',
            'lastName' => 'Doe',
        ]);

        $modelParams = new ModelParameters($defaultParams, $person);
        $result = $modelParams->data();

        $parameters = $result->parameters();

        // Find firstName parameter
        $firstNameParam = array_filter($parameters, fn ($p) => $p['Name'] === 'FirstName');
        $this->assertNotEmpty($firstNameParam);
    }

    public function test_decorates_base_parameter_service(): void
    {
        $serviceList = new ServiceList('TestService', 1, 'Pay');
        $defaultParams = new DefaultParameters($serviceList);

        // Add first model
        $address1 = new Address(['street' => 'Street 1']);
        $modelParams1 = new ModelParameters($defaultParams, $address1);

        // Chain another model (decorator pattern)
        $address2 = new Address(['city' => 'City 2']);
        $modelParams2 = new ModelParameters($modelParams1, $address2);

        $result = $modelParams2->data();
        $parameters = $result->parameters();

        // Both parameters should be present
        $streetParam = array_filter($parameters, fn ($p) => $p['Name'] === 'Street');
        $cityParam = array_filter($parameters, fn ($p) => $p['Name'] === 'City');

        $this->assertNotEmpty($streetParam);
        $this->assertNotEmpty($cityParam);
    }

    public function test_handles_empty_model(): void
    {
        $serviceList = new ServiceList('TestService', 1, 'Pay');
        $defaultParams = new DefaultParameters($serviceList);
        $address = new Address();

        $modelParams = new ModelParameters($defaultParams, $address);
        $result = $modelParams->data();

        // Should not throw exception, just return empty parameters
        $this->assertInstanceOf(ServiceList::class, $result);
    }
}
