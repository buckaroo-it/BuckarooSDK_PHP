<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use Buckaroo\Models\Address;
use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceList;
use Tests\TestCase;

class ServiceListTest extends TestCase
{
    public function test_constructor_initializes_all_properties(): void
    {
        $serviceList = new ServiceList('ideal', 2, 'Pay');

        $this->assertSame('ideal', $serviceList->name);
        $this->assertSame(2, $serviceList->version);
        $this->assertSame('Pay', $serviceList->action);
        $this->assertIsArray($serviceList->parameters());
        $this->assertEmpty($serviceList->parameters());
    }

    public function test_constructor_without_model_does_not_trigger_decoration(): void
    {
        $serviceList = new ServiceList('creditcard', 1, 'Authorize', null);

        $this->assertSame('creditcard', $serviceList->name);
        $this->assertSame(1, $serviceList->version);
        $this->assertSame('Authorize', $serviceList->action);
        $this->assertEmpty($serviceList->parameters());
    }

    public function test_constructor_with_model_triggers_decoration(): void
    {
        $address = new Address([
            'street' => 'Main Street',
            'houseNumber' => '123',
            'zipcode' => '1234AB',
            'city' => 'Amsterdam',
            'country' => 'NL',
        ]);

        $serviceList = new ServiceList('ideal', 2, 'Pay', $address);

        $this->assertSame('ideal', $serviceList->name);
        $this->assertNotEmpty($serviceList->parameters());
    }

    public function test_appendParameter_without_key_appends_to_array(): void
    {
        $serviceList = new ServiceList('test', 1, 'action');

        $serviceList->appendParameter(['Name' => 'param1', 'Value' => 'value1']);
        $serviceList->appendParameter(['Name' => 'param2', 'Value' => 'value2']);

        $params = $serviceList->parameters();

        $this->assertCount(2, $params);
        $this->assertSame(['Name' => 'param1', 'Value' => 'value1'], $params[0]);
        $this->assertSame(['Name' => 'param2', 'Value' => 'value2'], $params[1]);
    }

    public function test_appendParameter_with_key_sets_specific_index(): void
    {
        $serviceList = new ServiceList('test', 1, 'action');

        $serviceList->appendParameter(['Name' => 'amount', 'Value' => '10.50'], 'payment_amount');
        $serviceList->appendParameter(['Name' => 'currency', 'Value' => 'EUR'], 'payment_currency');

        $params = $serviceList->parameters();

        $this->assertArrayHasKey('payment_amount', $params);
        $this->assertArrayHasKey('payment_currency', $params);
        $this->assertSame(['Name' => 'amount', 'Value' => '10.50'], $params['payment_amount']);
        $this->assertSame(['Name' => 'currency', 'Value' => 'EUR'], $params['payment_currency']);
    }

    public function test_appendParameter_with_nested_arrays_iterates_recursively(): void
    {
        $serviceList = new ServiceList('test', 1, 'action');

        $nestedParams = [
            ['Name' => 'item1', 'Value' => 'value1'],
            ['Name' => 'item2', 'Value' => 'value2'],
            ['Name' => 'item3', 'Value' => 'value3'],
        ];

        $serviceList->appendParameter($nestedParams);

        $params = $serviceList->parameters();

        $this->assertCount(3, $params);
        $this->assertSame(['Name' => 'item1', 'Value' => 'value1'], $params[0]);
        $this->assertSame(['Name' => 'item2', 'Value' => 'value2'], $params[1]);
        $this->assertSame(['Name' => 'item3', 'Value' => 'value3'], $params[2]);
    }

    public function test_appendParameter_with_nested_arrays_and_key_overwrites(): void
    {
        $serviceList = new ServiceList('test', 1, 'action');

        $nestedParams = [
            ['Name' => 'param1', 'Value' => 'val1'],
            ['Name' => 'param2', 'Value' => 'val2'],
        ];

        $serviceList->appendParameter($nestedParams, 'batch');

        $params = $serviceList->parameters();

        $this->assertCount(1, $params);
        $this->assertArrayHasKey('batch', $params);
        $this->assertSame(['Name' => 'param2', 'Value' => 'val2'], $params['batch']);
    }

    public function test_appendParameter_multiple_calls_accumulate(): void
    {
        $serviceList = new ServiceList('test', 1, 'action');

        $serviceList->appendParameter(['Name' => 'first', 'Value' => '1']);
        $serviceList->appendParameter(['Name' => 'second', 'Value' => '2']);
        $serviceList->appendParameter(['Name' => 'third', 'Value' => '3']);

        $params = $serviceList->parameters();

        $this->assertCount(3, $params);
    }

    public function test_appendParameter_returns_fluent_interface(): void
    {
        $serviceList = new ServiceList('test', 1, 'action');

        $result = $serviceList->appendParameter(['Name' => 'test', 'Value' => 'value']);

        $this->assertSame($serviceList, $result);
    }

    public function test_appendParameter_fluent_chaining(): void
    {
        $serviceList = new ServiceList('test', 1, 'action');

        $serviceList
            ->appendParameter(['Name' => 'param1', 'Value' => 'val1'])
            ->appendParameter(['Name' => 'param2', 'Value' => 'val2'])
            ->appendParameter(['Name' => 'param3', 'Value' => 'val3']);

        $params = $serviceList->parameters();

        $this->assertCount(3, $params);
        $this->assertSame(['Name' => 'param1', 'Value' => 'val1'], $params[0]);
        $this->assertSame(['Name' => 'param2', 'Value' => 'val2'], $params[1]);
        $this->assertSame(['Name' => 'param3', 'Value' => 'val3'], $params[2]);
    }

    public function test_parameters_getter_returns_parameters_array(): void
    {
        $serviceList = new ServiceList('test', 1, 'action');

        $serviceList->appendParameter(['Name' => 'key', 'Value' => 'value']);

        $params = $serviceList->parameters();

        $this->assertIsArray($params);
        $this->assertCount(1, $params);
    }

    public function test_decorateParameters_with_address_model(): void
    {
        $address = new Address([
            'street' => 'Keizersgracht',
            'houseNumber' => '500',
            'zipcode' => '1017EK',
            'city' => 'Amsterdam',
            'country' => 'NL',
        ]);

        $serviceList = new ServiceList('ideal', 2, 'Pay', $address);

        $params = $serviceList->parameters();

        $this->assertNotEmpty($params);

        $paramValues = array_column($params, 'Value');
        $this->assertContains('Keizersgracht', $paramValues);
        $this->assertContains('500', $paramValues);
        $this->assertContains('1017EK', $paramValues);
        $this->assertContains('Amsterdam', $paramValues);
        $this->assertContains('NL', $paramValues);
    }

    public function test_decorateParameters_with_person_model(): void
    {
        $person = new Person([
            'firstName' => 'John',
            'lastName' => 'Doe',
            'gender' => 'Male',
            'culture' => 'nl-NL',
        ]);

        $serviceList = new ServiceList('creditcard', 1, 'Pay', $person);

        $params = $serviceList->parameters();

        $this->assertNotEmpty($params);

        $paramValues = array_column($params, 'Value');
        $this->assertContains('John', $paramValues);
        $this->assertContains('Doe', $paramValues);
        $this->assertContains('Male', $paramValues);
        $this->assertContains('nl-NL', $paramValues);
    }

    public function test_decorateParameters_transforms_property_names(): void
    {
        $address = new Address([
            'street' => 'Main Street',
            'houseNumber' => '42',
            'city' => 'Rotterdam',
        ]);

        $serviceList = new ServiceList('test', 1, 'Pay', $address);

        $params = $serviceList->parameters();

        $this->assertNotEmpty($params);

        $paramNames = array_column($params, 'Name');
        $this->assertContains('Street', $paramNames);
        $this->assertContains('HouseNumber', $paramNames);
        $this->assertContains('City', $paramNames);
    }

    public function test_empty_parameters_on_initialization(): void
    {
        $serviceList = new ServiceList('test', 1, 'action');

        $params = $serviceList->parameters();

        $this->assertIsArray($params);
        $this->assertEmpty($params);
    }

    public function test_appendParameter_with_single_value_array(): void
    {
        $serviceList = new ServiceList('test', 1, 'action');

        $serviceList->appendParameter(['Name' => 'single', 'Value' => 'value']);

        $params = $serviceList->parameters();

        $this->assertCount(1, $params);
        $this->assertSame(['Name' => 'single', 'Value' => 'value'], $params[0]);
    }

    public function test_large_parameter_array(): void
    {
        $serviceList = new ServiceList('test', 1, 'action');

        $largeArray = [];
        for ($i = 0; $i < 100; $i++)
        {
            $largeArray[] = ['Name' => 'param' . $i, 'Value' => 'value' . $i];
        }

        $serviceList->appendParameter($largeArray);

        $params = $serviceList->parameters();

        $this->assertCount(100, $params);
        $this->assertSame(['Name' => 'param0', 'Value' => 'value0'], $params[0]);
        $this->assertSame(['Name' => 'param99', 'Value' => 'value99'], $params[99]);
    }

    public function test_appendParameter_preserves_existing_parameters(): void
    {
        $serviceList = new ServiceList('test', 1, 'action');

        $serviceList->appendParameter(['Name' => 'existing', 'Value' => 'old']);
        $serviceList->appendParameter(['Name' => 'new', 'Value' => 'fresh']);

        $params = $serviceList->parameters();

        $this->assertCount(2, $params);
        $this->assertSame(['Name' => 'existing', 'Value' => 'old'], $params[0]);
        $this->assertSame(['Name' => 'new', 'Value' => 'fresh'], $params[1]);
    }

    public function test_appendParameter_with_mixed_keys_and_no_keys(): void
    {
        $serviceList = new ServiceList('test', 1, 'action');

        $serviceList->appendParameter(['Name' => 'indexed1', 'Value' => 'val1']);
        $serviceList->appendParameter(['Name' => 'keyed', 'Value' => 'val2'], 'my_key');
        $serviceList->appendParameter(['Name' => 'indexed2', 'Value' => 'val3']);

        $params = $serviceList->parameters();

        $this->assertCount(3, $params);
        $this->assertSame(['Name' => 'indexed1', 'Value' => 'val1'], $params[0]);
        $this->assertArrayHasKey('my_key', $params);
        $this->assertSame(['Name' => 'keyed', 'Value' => 'val2'], $params['my_key']);
        $this->assertSame(['Name' => 'indexed2', 'Value' => 'val3'], $params[1]);
    }

    public function test_constructor_with_different_action_values(): void
    {
        $actions = ['Pay', 'Authorize', 'Refund', 'Cancel', 'Capture'];

        foreach ($actions as $action)
        {
            $serviceList = new ServiceList('test', 1, $action);
            $this->assertSame($action, $serviceList->action);
        }
    }

    public function test_constructor_with_different_version_numbers(): void
    {
        $versions = [1, 2, 3, 10, 99];

        foreach ($versions as $version)
        {
            $serviceList = new ServiceList('test', $version, 'Pay');
            $this->assertSame($version, $serviceList->version);
        }
    }

    public function test_deeply_nested_arrays_are_flattened(): void
    {
        $serviceList = new ServiceList('test', 1, 'action');

        $deeplyNested = [
            [
                ['Name' => 'nested1', 'Value' => 'val1'],
                ['Name' => 'nested2', 'Value' => 'val2'],
            ],
            [
                ['Name' => 'nested3', 'Value' => 'val3'],
            ],
        ];

        $serviceList->appendParameter($deeplyNested);

        $params = $serviceList->parameters();

        $this->assertCount(3, $params);
        $this->assertSame(['Name' => 'nested1', 'Value' => 'val1'], $params[0]);
        $this->assertSame(['Name' => 'nested2', 'Value' => 'val2'], $params[1]);
        $this->assertSame(['Name' => 'nested3', 'Value' => 'val3'], $params[2]);
    }

    public function test_appendParameter_with_empty_array_appends_empty_entry(): void
    {
        $serviceList = new ServiceList('test', 1, 'action');

        $emptyArray = [];
        $serviceList->appendParameter($emptyArray);

        $params = $serviceList->parameters();

        $this->assertCount(1, $params);
        $this->assertIsArray($params[0]);
        $this->assertEmpty($params[0]);
    }

    public function test_magic_property_access_to_name_version_action(): void
    {
        $serviceList = new ServiceList('paypal', 5, 'Capture');

        $this->assertSame('paypal', $serviceList->name);
        $this->assertSame(5, $serviceList->version);
        $this->assertSame('Capture', $serviceList->action);
    }
}
