<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Paypal\Models;

use Buckaroo\Models\Person;
use Buckaroo\PaymentMethods\Paypal\Models\ExtraInfo;
use Buckaroo\PaymentMethods\Paypal\Service\ParameterKeys\AddressAdapter;
use Buckaroo\PaymentMethods\Paypal\Service\ParameterKeys\PhoneAdapter;
use Tests\TestCase;

class ExtraInfoTest extends TestCase
{
    /** @test */
    public function it_sets_address_from_array(): void
    {
        $extraInfo = new ExtraInfo([
            'address' => [
                'street' => 'Main Street',
                'houseNumber' => '123',
                'zipcode' => '1234AB',
                'city' => 'Amsterdam',
                'country' => 'NL',
            ],
        ]);

        $address = $extraInfo->address();

        $this->assertInstanceOf(AddressAdapter::class, $address);
    }

    /** @test */
    public function it_returns_address_without_parameter(): void
    {
        $extraInfo = new ExtraInfo([
            'address' => [
                'street' => 'Test Street',
            ],
        ]);

        $address = $extraInfo->address();
        $this->assertInstanceOf(AddressAdapter::class, $address);

        $sameAddress = $extraInfo->address(null);
        $this->assertSame($address, $sameAddress);
    }

    /** @test */
    public function it_sets_customer_from_array(): void
    {
        $extraInfo = new ExtraInfo([
            'customer' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
        ]);

        $customer = $extraInfo->customer();

        $this->assertInstanceOf(Person::class, $customer);
    }

    /** @test */
    public function it_returns_customer_without_parameter(): void
    {
        $extraInfo = new ExtraInfo([
            'customer' => [
                'firstName' => 'Jane',
                'lastName' => 'Smith',
            ],
        ]);

        $customer = $extraInfo->customer();
        $this->assertInstanceOf(Person::class, $customer);

        $sameCustomer = $extraInfo->customer(null);
        $this->assertSame($customer, $sameCustomer);
    }

    /** @test */
    public function it_sets_phone_from_array(): void
    {
        $extraInfo = new ExtraInfo([
            'phone' => [
                'mobile' => '0612345678',
            ],
        ]);

        $phone = $extraInfo->phone();

        $this->assertInstanceOf(PhoneAdapter::class, $phone);
    }

    /** @test */
    public function it_returns_phone_without_parameter(): void
    {
        $extraInfo = new ExtraInfo([
            'phone' => [
                'mobile' => '0698765432',
            ],
        ]);

        $phone = $extraInfo->phone();
        $this->assertInstanceOf(PhoneAdapter::class, $phone);

        $samePhone = $extraInfo->phone(null);
        $this->assertSame($phone, $samePhone);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $extraInfo = new ExtraInfo([
            'noShipping' => '1',
            'addressOverride' => true,
            'address' => [
                'street' => 'Main Street',
                'houseNumber' => '123',
                'zipcode' => '1234AB',
                'city' => 'Amsterdam',
                'country' => 'NL',
            ],
            'customer' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
            'phone' => [
                'mobile' => '0612345678',
            ],
        ]);

        $this->assertInstanceOf(AddressAdapter::class, $extraInfo->address());
        $this->assertInstanceOf(Person::class, $extraInfo->customer());
        $this->assertInstanceOf(PhoneAdapter::class, $extraInfo->phone());
    }
}
