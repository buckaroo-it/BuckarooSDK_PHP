<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Afterpay\Service\ParameterKeys;

use Buckaroo\Models\Person;
use Buckaroo\PaymentMethods\Afterpay\Service\ParameterKeys\RecipientAdapter;
use Tests\TestCase;

class RecipientAdapterTest extends TestCase
{
    public function test_transforms_title_to_salutation(): void
    {
        $person = new Person(['title' => 'Mr']);
        $adapter = new RecipientAdapter($person);

        $this->assertSame('Salutation', $adapter->serviceParameterKeyOf('title'));
    }

    public function test_transforms_chamber_of_commerce_to_identification_number(): void
    {
        // Note: chamberOfCommerce is a key mapping, doesn't need to exist on model
        $person = new Person([]);
        $adapter = new RecipientAdapter($person);

        $this->assertSame('IdentificationNumber', $adapter->serviceParameterKeyOf('chamberOfCommerce'));
    }

    public function test_uses_ucfirst_for_unmapped_properties(): void
    {
        $person = new Person(['firstName' => 'John']);
        $adapter = new RecipientAdapter($person);

        $this->assertSame('FirstName', $adapter->serviceParameterKeyOf('firstName'));
        $this->assertSame('LastName', $adapter->serviceParameterKeyOf('lastName'));
        $this->assertSame('Gender', $adapter->serviceParameterKeyOf('gender'));
    }

    public function test_proxies_property_access_to_model(): void
    {
        $person = new Person([
            'title' => 'Mr',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'gender' => '1',
        ]);

        $adapter = new RecipientAdapter($person);

        $this->assertSame('Mr', $adapter->title);
        $this->assertSame('John', $adapter->firstName);
        $this->assertSame('Doe', $adapter->lastName);
        $this->assertSame('1', $adapter->gender);
    }

    public function test_delegates_to_array_to_model(): void
    {
        $person = new Person([
            'title' => 'Mrs',
            'firstName' => 'Jane',
            'lastName' => 'Smith',
        ]);

        $adapter = new RecipientAdapter($person);
        $array = $adapter->toArray();

        $this->assertIsArray($array);
        $this->assertSame('Mrs', $array['title']);
        $this->assertSame('Jane', $array['firstName']);
        $this->assertSame('Smith', $array['lastName']);
    }

    public function test_implements_recipient_interface(): void
    {
        $person = new Person(['firstName' => 'Test']);
        $adapter = new RecipientAdapter($person);

        $this->assertInstanceOf(\Buckaroo\Models\Interfaces\Recipient::class, $adapter);
    }

    public function test_all_key_mappings_are_correct(): void
    {
        $person = new Person(['title' => 'Dr']);
        $adapter = new RecipientAdapter($person);

        $expectedMappings = [
            'title' => 'Salutation',
            'chamberOfCommerce' => 'IdentificationNumber',
        ];

        foreach ($expectedMappings as $property => $expectedKey) {
            $this->assertSame($expectedKey, $adapter->serviceParameterKeyOf($property));
        }
    }

    public function test_handles_various_title_formats(): void
    {
        $titles = ['Mr', 'Mrs', 'Ms', 'Dr', 'Prof', 'Sir', 'Madam'];

        foreach ($titles as $title) {
            $person = new Person(['title' => $title]);
            $adapter = new RecipientAdapter($person);

            $this->assertSame($title, $adapter->title);
            $this->assertSame('Salutation', $adapter->serviceParameterKeyOf('title'));
        }
    }
}
