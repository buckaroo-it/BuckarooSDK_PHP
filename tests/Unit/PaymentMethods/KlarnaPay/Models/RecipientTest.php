<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\KlarnaPay\Models;

use Buckaroo\Models\Email;
use Buckaroo\Models\Person;
use Buckaroo\PaymentMethods\KlarnaPay\Models\Recipient;
use Buckaroo\PaymentMethods\KlarnaPay\Service\ParameterKeys\AddressAdapter;
use Buckaroo\PaymentMethods\KlarnaPay\Service\ParameterKeys\PhoneAdapter;
use Tests\TestCase;

class RecipientTest extends TestCase
{
    /** @test */
    public function it_creates_person_recipient(): void
    {
        $recipient = new Recipient('Billing', [
            'recipient' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
        ]);

        $this->assertInstanceOf(Person::class, $recipient->recipient());
    }

    /** @test */
    public function it_sets_and_returns_address_adapter(): void
    {
        $recipient = new Recipient('Billing', [
            'address' => [
                'street' => 'Main Street',
                'houseNumber' => '123',
            ],
        ]);

        $this->assertInstanceOf(AddressAdapter::class, $recipient->address());
    }

    /** @test */
    public function it_sets_and_returns_phone_adapter(): void
    {
        $recipient = new Recipient('Billing', [
            'phone' => [
                'mobile' => '0612345678',
            ],
        ]);

        $this->assertInstanceOf(PhoneAdapter::class, $recipient->phone());
    }

    /** @test */
    public function it_sets_and_returns_email(): void
    {
        $recipient = new Recipient('Billing', [
            'email' => 'john@example.com',
        ]);

        $this->assertInstanceOf(Email::class, $recipient->email());
    }

    /** @test */
    public function it_returns_group_type_with_customer_suffix(): void
    {
        $billingRecipient = new Recipient('Billing', []);
        $shippingRecipient = new Recipient('Shipping', []);

        $this->assertSame('BillingCustomer', $billingRecipient->getGroupType('any'));
        $this->assertSame('ShippingCustomer', $shippingRecipient->getGroupType('any'));
    }
}
