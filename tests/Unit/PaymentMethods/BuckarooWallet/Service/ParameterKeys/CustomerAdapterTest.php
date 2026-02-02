<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\BuckarooWallet\Service\ParameterKeys;

use Buckaroo\Models\Person;
use Buckaroo\PaymentMethods\BuckarooWallet\Service\ParameterKeys\CustomerAdapter;
use Tests\TestCase;

class CustomerAdapterTest extends TestCase
{
    public function test_transforms_first_name_to_consumer_first_name(): void
    {
        $person = new Person(['firstName' => 'John']);
        $adapter = new CustomerAdapter($person);

        $this->assertSame('ConsumerFirstName', $adapter->serviceParameterKeyOf('firstName'));
    }

    public function test_transforms_last_name_to_consumer_last_name(): void
    {
        $person = new Person(['lastName' => 'Doe']);
        $adapter = new CustomerAdapter($person);

        $this->assertSame('ConsumerLastName', $adapter->serviceParameterKeyOf('lastName'));
    }

    public function test_uses_ucfirst_for_unmapped_properties(): void
    {
        $person = new Person(['gender' => '1']);
        $adapter = new CustomerAdapter($person);

        $this->assertSame('Gender', $adapter->serviceParameterKeyOf('gender'));
        $this->assertSame('BirthDate', $adapter->serviceParameterKeyOf('birthDate'));
    }

    public function test_proxies_property_access_to_model(): void
    {
        $person = new Person([
            'firstName' => 'Jane',
            'lastName' => 'Smith',
            'gender' => '2',
            'birthDate' => '1990-01-15',
        ]);

        $adapter = new CustomerAdapter($person);

        $this->assertSame('Jane', $adapter->firstName);
        $this->assertSame('Smith', $adapter->lastName);
        $this->assertSame('2', $adapter->gender);
        $this->assertSame('1990-01-15', $adapter->birthDate);
    }

    public function test_delegates_to_array_to_model(): void
    {
        $person = new Person([
            'firstName' => 'Bob',
            'lastName' => 'Johnson',
        ]);

        $adapter = new CustomerAdapter($person);
        $array = $adapter->toArray();

        $this->assertIsArray($array);
        $this->assertSame('Bob', $array['firstName']);
        $this->assertSame('Johnson', $array['lastName']);
    }

    public function test_all_key_mappings_are_correct(): void
    {
        $person = new Person([
            'firstName' => 'Alice',
            'lastName' => 'Brown',
        ]);

        $adapter = new CustomerAdapter($person);

        $expectedMappings = [
            'firstName' => 'ConsumerFirstName',
            'lastName' => 'ConsumerLastName',
        ];

        foreach ($expectedMappings as $property => $expectedKey) {
            $this->assertSame($expectedKey, $adapter->serviceParameterKeyOf($property));
        }
    }
}
