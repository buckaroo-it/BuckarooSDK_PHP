<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Klarna\Models;

use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Klarna\Models\Recipient;
use Buckaroo\PaymentMethods\Klarna\Service\ParameterKeys\AddressAdapter;
use Buckaroo\PaymentMethods\Klarna\Service\ParameterKeys\EmailAdapter;
use Buckaroo\PaymentMethods\Klarna\Service\ParameterKeys\PhoneAdapter;
use Buckaroo\PaymentMethods\Klarna\Service\ParameterKeys\RecipientAdapter;
use Tests\TestCase;

class RecipientTest extends TestCase
{
    /** @test */
    public function it_extends_service_parameter(): void
    {
        $recipient = new Recipient('Billing');

        $this->assertInstanceOf(ServiceParameter::class, $recipient);
    }

    /** @test */
    public function it_sets_type_in_constructor(): void
    {
        $recipient = new Recipient('Shipping');

        $reflection = new \ReflectionClass($recipient);
        $property = $reflection->getProperty('type');
        $property->setAccessible(true);

        $this->assertSame('Shipping', $property->getValue($recipient));
    }

    /** @test */
    public function it_accepts_billing_type(): void
    {
        $recipient = new Recipient('Billing');

        $reflection = new \ReflectionClass($recipient);
        $property = $reflection->getProperty('type');
        $property->setAccessible(true);

        $this->assertSame('Billing', $property->getValue($recipient));
    }

    /** @test */
    public function it_sets_recipient_from_array(): void
    {
        $recipient = new Recipient('Billing', [
            'recipient' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
        ]);

        $recipientAdapter = $recipient->recipient();

        $this->assertInstanceOf(RecipientAdapter::class, $recipientAdapter);
    }

    /** @test */
    public function it_sets_address_from_array(): void
    {
        $recipient = new Recipient('Billing', [
            'address' => [
                'street' => 'Main Street',
                'houseNumber' => '123',
                'zipcode' => '1234AB',
                'city' => 'Amsterdam',
                'country' => 'NL',
            ],
        ]);

        $address = $recipient->address();

        $this->assertInstanceOf(AddressAdapter::class, $address);
    }

    /** @test */
    public function it_sets_phone_from_array(): void
    {
        $recipient = new Recipient('Billing', [
            'phone' => [
                'mobile' => '0612345678',
            ],
        ]);

        $phone = $recipient->phone();

        $this->assertInstanceOf(PhoneAdapter::class, $phone);
    }

    /** @test */
    public function it_sets_email_from_string(): void
    {
        $recipient = new Recipient('Billing', [
            'email' => 'john.doe@example.com',
        ]);

        $email = $recipient->email();

        $this->assertInstanceOf(EmailAdapter::class, $email);
    }

    /** @test */
    public function it_handles_complete_recipient_data(): void
    {
        $recipient = new Recipient('Billing', [
            'recipient' => [
                'firstName' => 'Jane',
                'lastName' => 'Smith',
                'birthDate' => '1990-01-01',
            ],
            'address' => [
                'street' => 'Keizersgracht',
                'houseNumber' => '456',
                'houseNumberAdditional' => 'B',
                'zipcode' => '1016DK',
                'city' => 'Amsterdam',
                'country' => 'NL',
            ],
            'phone' => [
                'mobile' => '0687654321',
                'landline' => '0201234567',
            ],
            'email' => 'jane.smith@example.com',
        ]);

        $this->assertInstanceOf(RecipientAdapter::class, $recipient->recipient());
        $this->assertInstanceOf(AddressAdapter::class, $recipient->address());
        $this->assertInstanceOf(PhoneAdapter::class, $recipient->phone());
        $this->assertInstanceOf(EmailAdapter::class, $recipient->email());
    }

    /** @test */
    public function it_works_with_shipping_type(): void
    {
        $recipient = new Recipient('Shipping', [
            'recipient' => [
                'firstName' => 'Bob',
                'lastName' => 'Johnson',
            ],
            'address' => [
                'street' => 'Shipping Lane',
                'houseNumber' => '789',
                'zipcode' => '5678CD',
                'city' => 'Rotterdam',
                'country' => 'NL',
            ],
        ]);

        $reflection = new \ReflectionClass($recipient);
        $property = $reflection->getProperty('type');
        $property->setAccessible(true);

        $this->assertSame('Shipping', $property->getValue($recipient));
        $this->assertInstanceOf(RecipientAdapter::class, $recipient->recipient());
        $this->assertInstanceOf(AddressAdapter::class, $recipient->address());
    }

    /** @test */
    public function it_handles_uninitialized_properties(): void
    {
        $recipient = new Recipient('Billing', [
            'recipient' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
        ]);

        // Only recipient should be set
        $this->assertInstanceOf(RecipientAdapter::class, $recipient->recipient());
    }
}
