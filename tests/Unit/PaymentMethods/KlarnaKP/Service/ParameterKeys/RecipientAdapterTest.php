<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\KlarnaKP\Service\ParameterKeys;

use Buckaroo\Models\Person;
use Buckaroo\PaymentMethods\KlarnaKP\Service\ParameterKeys\RecipientAdapter;
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
}
