<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Klarna\Service\ParameterKeys;

use Buckaroo\Models\Person;
use Buckaroo\PaymentMethods\Klarna\Service\ParameterKeys\RecipientAdapter;
use Tests\TestCase;

class RecipientAdapterTest extends TestCase
{
    /** @test */
    public function it_creates_instance_with_model_and_type(): void
    {
        $model = new Person(['firstName' => 'John', 'lastName' => 'Doe']);
        $adapter = new RecipientAdapter($model, 'Billing');

        $this->assertInstanceOf(RecipientAdapter::class, $adapter);
    }

    /** @test */
    public function it_prepends_type_to_property_name(): void
    {
        $model = new Person(['firstName' => 'John']);
        $adapter = new RecipientAdapter($model, 'Billing');

        $key = $adapter->serviceParameterKeyOf('firstName');

        $this->assertSame('BillingFirstName', $key);
    }

    /** @test */
    public function it_prepends_shipping_type_to_property_name(): void
    {
        $model = new Person(['lastName' => 'Doe']);
        $adapter = new RecipientAdapter($model, 'Shipping');

        $key = $adapter->serviceParameterKeyOf('lastName');

        $this->assertSame('ShippingLastName', $key);
    }

    /** @test */
    public function it_capitalizes_property_name_when_not_in_keys_mapping(): void
    {
        $model = new Person(['culture' => 'nl-NL']);
        $adapter = new RecipientAdapter($model, 'Billing');

        $key = $adapter->serviceParameterKeyOf('culture');

        $this->assertSame('BillingCulture', $key);
    }

    /** @test */
    public function it_handles_already_capitalized_property_names(): void
    {
        $model = new Person(['FirstName' => 'John']);
        $adapter = new RecipientAdapter($model, 'Billing');

        $key = $adapter->serviceParameterKeyOf('FirstName');

        $this->assertSame('BillingFirstName', $key);
    }

    /** @test */
    public function it_works_with_empty_model(): void
    {
        $model = new Person([]);
        $adapter = new RecipientAdapter($model, 'Billing');

        $key = $adapter->serviceParameterKeyOf('anyProperty');

        $this->assertSame('BillingAnyProperty', $key);
    }

    /** @test */
    public function it_handles_multiple_properties(): void
    {
        $model = new Person([
            'firstName' => 'Jane',
            'lastName' => 'Smith',
            'birthDate' => '1990-01-01',
        ]);
        $adapter = new RecipientAdapter($model, 'Billing');

        $firstNameKey = $adapter->serviceParameterKeyOf('firstName');
        $lastNameKey = $adapter->serviceParameterKeyOf('lastName');
        $birthDateKey = $adapter->serviceParameterKeyOf('birthDate');

        $this->assertSame('BillingFirstName', $firstNameKey);
        $this->assertSame('BillingLastName', $lastNameKey);
        $this->assertSame('BillingBirthDate', $birthDateKey);
    }

    /** @test */
    public function it_works_with_shipping_type_for_multiple_properties(): void
    {
        $model = new Person([
            'firstName' => 'Bob',
            'gender' => 'Male',
        ]);
        $adapter = new RecipientAdapter($model, 'Shipping');

        $firstNameKey = $adapter->serviceParameterKeyOf('firstName');
        $genderKey = $adapter->serviceParameterKeyOf('gender');

        $this->assertSame('ShippingFirstName', $firstNameKey);
        $this->assertSame('ShippingGender', $genderKey);
    }
}
