<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\KlarnaKP\Models;

use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\KlarnaKP\Models\Recipient;
use Buckaroo\PaymentMethods\KlarnaKP\Service\ParameterKeys\AddressAdapter;
use Buckaroo\PaymentMethods\KlarnaKP\Service\ParameterKeys\EmailAdapter;
use Buckaroo\PaymentMethods\KlarnaKP\Service\ParameterKeys\PhoneAdapter;
use Buckaroo\PaymentMethods\KlarnaKP\Service\ParameterKeys\RecipientAdapter;
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
    public function it_sets_all_properties_together(): void
    {
        $recipient = new Recipient('Shipping', [
            'recipient' => [
                'firstName' => 'Jane',
                'lastName' => 'Smith',
            ],
            'address' => [
                'street' => 'Second Street',
                'houseNumber' => '456',
                'zipcode' => '5678CD',
                'city' => 'Rotterdam',
                'country' => 'NL',
            ],
            'phone' => [
                'mobile' => '0687654321',
            ],
            'email' => 'jane.smith@example.com',
        ]);

        $this->assertInstanceOf(RecipientAdapter::class, $recipient->recipient());
        $this->assertInstanceOf(AddressAdapter::class, $recipient->address());
        $this->assertInstanceOf(PhoneAdapter::class, $recipient->phone());
        $this->assertInstanceOf(EmailAdapter::class, $recipient->email());
    }

    /** @test */
    public function it_returns_existing_recipient_when_called_without_parameter(): void
    {
        $recipient = new Recipient('Billing', [
            'recipient' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
        ]);

        $first = $recipient->recipient();
        $second = $recipient->recipient();

        $this->assertSame($first, $second);
    }

    /** @test */
    public function it_returns_existing_address_when_called_without_parameter(): void
    {
        $recipient = new Recipient('Billing', [
            'address' => [
                'street' => 'Test Street',
                'houseNumber' => '1',
            ],
        ]);

        $first = $recipient->address();
        $second = $recipient->address();

        $this->assertSame($first, $second);
    }

    /** @test */
    public function it_returns_existing_phone_when_called_without_parameter(): void
    {
        $recipient = new Recipient('Billing', [
            'phone' => [
                'mobile' => '0600000000',
            ],
        ]);

        $first = $recipient->phone();
        $second = $recipient->phone();

        $this->assertSame($first, $second);
    }

    /** @test */
    public function it_returns_existing_email_when_called_without_parameter(): void
    {
        $recipient = new Recipient('Billing', [
            'email' => 'test@test.com',
        ]);

        $first = $recipient->email();
        $second = $recipient->email();

        $this->assertSame($first, $second);
    }

    /** @test */
    public function it_handles_empty_values_in_constructor(): void
    {
        $recipient = new Recipient('Billing', []);

        $this->assertInstanceOf(Recipient::class, $recipient);
    }

    /** @test */
    public function it_handles_null_values_in_constructor(): void
    {
        $recipient = new Recipient('Billing', null);

        $this->assertInstanceOf(Recipient::class, $recipient);
    }
}
